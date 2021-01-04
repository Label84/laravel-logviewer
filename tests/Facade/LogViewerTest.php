<?php

namespace Label84\LogViewer\Tests\Facade;

use Label84\LogViewer\Facades\LogViewer;
use Label84\LogViewer\Tests\TestCase;

class LogViewerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_set_the_file()
    {
        config()->set('logviewer.log_files_directory', 'tests/dummy_data/storage/logs');

        $files = LogViewer::files();

        $logs = LogViewer::setFile(end($files))->logs();

        $this->assertEquals(end($files), basename($logs->first()->path));
    }

    /** @test */
    public function it_can_set_the_path()
    {
        $logs = LogViewer::setPath('tests/dummy_data/storage/logs')->setChannel('daily')->logs();

        $this->assertEquals('laravel-2021-01-02.log', basename($logs->first()->path));
    }

    /** @test */
    public function it_can_set_the_channel()
    {
        config()->set('logviewer.log_files_directory', 'tests/dummy_data/storage/logs');

        $logs = LogViewer::setChannel('single')->logs();

        $this->assertEquals('laravel.log', basename($logs->first()->path));
    }
}
