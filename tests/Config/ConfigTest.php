<?php

namespace Label84\LogViewer\Tests\Config;

use Label84\LogViewer\Facades\LogViewer;
use Label84\LogViewer\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_list_files_from_directory()
    {
        config()->set('logviewer.log_files_directory', 'tests/dummy_data/storage/logs');

        $files = LogViewer::files();

        $this->assertEquals(3, sizeof($files));
    }
}
