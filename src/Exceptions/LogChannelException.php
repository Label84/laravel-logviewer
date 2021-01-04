<?php

namespace Label84\LogViewer\Exceptions;

use Exception;

final class LogChannelException extends Exception
{
    public static function channelNotSupported(string $channel): self
    {
        return new self("The channel '{$channel}' is not supported.");
    }
}
