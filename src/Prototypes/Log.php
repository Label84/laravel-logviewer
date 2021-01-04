<?php

namespace Label84\LogViewer\Prototypes;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Log
{
    const HEADING_PATTERN = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/';
    const BACKUP_HEADING_PATTERN = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+)/';

    public Carbon $date;
    public string $logger;
    public string $level;
    public ?string $message;
    public ?string $context;
    public ?string $extra;
    protected string $heading;
    protected string $stack;
    public string $path;

    public function __construct(string $heading, string $stack, string $path)
    {
        $this->heading = $heading;
        $this->stack = $stack;
        $this->path = $path;

        $this->parse($this->heading);
    }

    protected function parse(string $heading): void
    {
        if (strlen($heading) === 0) {
            return;
        }

        preg_match(self::HEADING_PATTERN, $heading, $data);

        if (!isset($data['date'])) {
            preg_match(self::BACKUP_HEADING_PATTERN, $heading, $data);
        }

        /** @var Carbon $date */
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $data['date']);

        $this->date = $date;
        $this->logger = $data['logger'];
        $this->level = Str::lower($data['level']);
        $this->message = $data['message'] ?? null;
        $this->context = json_decode($data['context'] ?? '', true) ?? null;
        $this->extra = json_decode($data['extra'] ?? '', true) ?? null;
    }

    public function isNew(): bool
    {
        return $this->date->isAfter(now()->subMinutes(config('logviewer.marked_as_new_in_minutes')));
    }

    public function getClassesForLevel(): string
    {
        return config('logviewer.classes')[$this->level] ?? '';
    }
}
