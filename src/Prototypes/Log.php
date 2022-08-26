<?php

namespace Label84\LogViewer\Prototypes;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Log
{
    public const HEADING_PATTERN = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/';
    public const BACKUP_HEADING_PATTERN = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>.*[^ ]+)/';

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

        if (! isset($data['date'])) {
            preg_match(self::BACKUP_HEADING_PATTERN, $heading, $data);
        }

        /** @var Carbon $date */
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $data['date']);

        $this->date = $date;
        $this->logger = $data['logger'];
        $this->level = Str::lower($data['level']);
        $this->message = $this->combineMessage($data);
        $this->context = isset($data['context']) ? (string) json_encode($data['context']) : null;
        $this->extra = isset($data['extra']) ? (string) json_encode($data['extra']) : null;
    }

    private function combineMessage(array $data): ?string
    {
        if (isset($data['context']) && isset($data['extra'])) {
            if (Str::length($data['context'].$data['extra']) < 100) {
                return Str::of($data['message'])
                    ->append(' ')->append($data['context'])
                    ->append(' ')->append($data['extra']);
            }
        }

        return $data['message'] ?? null;
    }

    public function isNew(): bool
    {
        return $this->date->isAfter(now()->subMinutes(config('logviewer.marked_as_new_in_minutes')));
    }

    public function getClassesForLevel(): string
    {
        $class = config('logviewer.classes')[$this->level] ?? '';

        // convert Bootstrap class to TailwindCSS classes for applications with an older published config file
        switch ($class) {
            case 'bg-primary':
                $class = 'bg-gray-100 text-gray-800';

                break;
            case 'bg-info':
                $class = 'bg-blue-100 text-blue-800';

                break;
            case 'bg-warning':
                $class = 'bg-yellow-100 text-yellow-800';

                break;
            case 'bg-danger':
                $class = 'bg-red-100 text-red-800';

                break;
        }

        return $class;
    }
}
