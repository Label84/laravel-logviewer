<?php

namespace Label84\LogViewer\Facades;

use Illuminate\Support\Facades\Facade;

class LogViewer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'logviewer';
    }
}
