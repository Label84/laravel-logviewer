<?php

namespace Label84\LogViewer\Tests;

use Label84\LogViewer\LogViewerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LogViewerServiceProvider::class,
        ];
    }
}
