<?php

namespace Label84\LogViewer\Exceptions;

use Exception;

final class LogFileException extends Exception
{
    public static function fileNotFound(string $path): self
    {
        return new self("File '{$path}' not found.");
    }

    public static function noPathSet(): self
    {
        return new self('No path set.');
    }

    public static function noFileSet(): self
    {
        return new self('No file set.');
    }
}
