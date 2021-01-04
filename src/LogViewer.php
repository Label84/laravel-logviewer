<?php

namespace Label84\LogViewer;

use Illuminate\Support\Facades\File;
use Label84\LogViewer\Exceptions\LogChannelException;
use Label84\LogViewer\Exceptions\LogFileException;
use Label84\LogViewer\Prototypes\Log;
use Label84\LogViewer\Support\LogViewerCollection;
use Label84\LogViewer\Support\LogViewerLevel;

class LogViewer
{
    const DATETIME_PATTERN = "/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/";

    protected string $path = '';

    public function setFile(string $file = null): self
    {
        if (!$file) {
            throw LogFileException::noFileSet();
        }

        $this->path = $this->getPath($file);

        return $this;
    }

    public function setPath(string $path): self
    {
        if (!$path) {
            throw LogFileException::noPathSet();
        }

        config()->set('logviewer.log_files_directory', $path);

        return $this;
    }

    public function setChannel(string $channel): self
    {
        if (!in_array($channel, ['single', 'daily'])) {
            throw LogChannelException::channelNotSupported(config('logviewer.log_channel') ?? '');
        }

        if ($channel == 'single') {
            $this->path = $this->getPath('laravel.log');
        } else {
            $files = $this->files('laravel-');
            $lastFile = end($files);

            $this->path = $this->getPath($lastFile);
        }

        return $this;
    }

    public function logs(): LogViewerCollection
    {
        if (!$this->path) {
            $this->setChannel(config('logviewer.log_channel'));
        }

        if (!$this->path) {
            throw LogFileException::noFileSet();
        }

        $content = $this->getFileContent($this->path);

        return $this->toCollection($content);
    }

    public function getPath(string $file = ''): string
    {
        if (!$this->path) {
            $this->path = config('logviewer.log_files_directory');
        }

        return $this->path.'/'.$file;
    }

    public function files(string $prefix = ''): array
    {
        /** @var array $files */
        $files = glob($this->getPath($prefix.'*.log'));

        foreach ($files as $i => $file) {
            $files[$i] = basename($file);
        }

        return $files;
    }

    protected function getFileContent(string $path): string
    {
        if (File::exists($path)) {
            return File::get($path);
        }

        throw LogFileException::fileNotFound($path);
    }

    protected function parseContent(string $content): array
    {
        preg_match_all(self::DATETIME_PATTERN, $content, $headings);

        /** @var array $data */
        $data = preg_split(self::DATETIME_PATTERN, $content);

        if ($data[0] < 1) {
            $trash = array_shift($data);
            unset($trash);
        }

        return [$headings, $data];
    }

    public function toArray(string $content): array
    {
        $logs = [];

        $parsedContent = $this->parseContent($content);
        $headings = $parsedContent[0];
        $data = $parsedContent[1];

        foreach ($headings as $heading) {
            for ($i = 0, $h = count($heading); $i < $h; $i++) {
                $logs[] = [$heading[$i], $data[$i]];
            }
        }

        unset($headings, $data);

        return array_reverse($logs);
    }

    public function toCollection(string $content): LogViewerCollection
    {
        $logs = new LogViewerCollection();

        $parsedContent = $this->parseContent($content);
        $headings = $parsedContent[0];
        $data = $parsedContent[1];

        foreach ($headings as $heading) {
            for ($i = 0, $h = count($heading); $i < $h; $i++) {
                $logs->push(new Log($heading[$i], $data[$i], $this->path));
            }
        }

        $logs->offsetUnset('heading'); // @phpstan-ignore-line
        $logs->offsetUnset('data'); // @phpstan-ignore-line

        $logs = $this->excludeLogsBelowThreshold($logs, config('logviewer.minimum_level') ?? LogViewerLevel::DEBUG);

        return $logs->reverse();
    }

    protected function excludeLogsBelowThreshold(LogViewerCollection $logs, int $threshold): LogViewerCollection
    {
        return $logs->whereMinLevel($threshold);
    }
}
