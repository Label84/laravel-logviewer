<?php

namespace Label84\LogViewer\Exceptions;

use Exception;

final class LogFileException extends Exception
{
    public static function fileNotFound(string $path): self
    {
        return new self("File '{$path}' not found.");
    }

    public static function fileSizeTooLarge(string $path, int $fileSize, int $maxAllowedFileSize): self
    {
        $fileSizeInMegeBytes = number_format(($fileSize / 1048576), 0);
        $maxAllowedFileSizeInMegeBytes = number_format(($maxAllowedFileSize / 1048576), 0);

        return new self("File size of {$path} ({$fileSizeInMegeBytes}MB) is larger than allowed {$maxAllowedFileSizeInMegeBytes}MB.");
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
