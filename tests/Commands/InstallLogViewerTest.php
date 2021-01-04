<?php

namespace Label84\LogViewer\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Label84\LogViewer\Tests\TestCase;

class InstallLogViewerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_copies_the_config_file_upon_installation()
    {
        if (File::exists(config_path('logviewer.php'))) {
            unlink(config_path('logviewer.php'));
        }

        $this->assertFalse(File::exists(config_path('logviewer.php')));

        Artisan::call('vendor:publish', [
            '--provider' => "Label84\LogViewer\LogViewerServiceProvider",
            '--tag' => 'config'
        ]);

        $this->assertTrue(File::exists(config_path('logviewer.php')));
    }
}
