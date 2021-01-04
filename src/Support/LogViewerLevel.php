<?php

namespace Label84\LogViewer\Support;

use Illuminate\Support\Str;

class LogViewerLevel
{
    public const DEBUG = 100;
    public const INFO = 200;
    public const NOTICE = 250;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;
    public const ALERT = 550;
    public const EMERGENCY = 600;

    protected const LEVELS = [
        'debug' => self::DEBUG,
        'info' => self::INFO,
        'notice' => self::NOTICE,
        'warning' => self::WARNING,
        'error' => self::ERROR,
        'critical' => self::CRITICAL,
        'alert' => self::ALERT,
        'emergency' => self::EMERGENCY,
    ];

    /** @param string|int $level */
    public static function get($level): int
    {
        if (is_numeric($level)) {
            return (int) $level;
        }

        return self::LEVELS[Str::lower($level)] ?? 100;
    }

    public static function levels(): array
    {
        return self::LEVELS;
    }
}
