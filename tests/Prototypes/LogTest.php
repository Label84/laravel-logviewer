<?php

namespace Label84\LogViewer\Tests\Prototypes;

use Carbon\Carbon;
use Label84\LogViewer\Exceptions\LogChannelException;
use Label84\LogViewer\Exceptions\LogFileException;
use Label84\LogViewer\Facades\LogViewer;
use Label84\LogViewer\Prototypes\Log;
use Label84\LogViewer\Support\LogViewerLevel;
use Label84\LogViewer\Tests\TestCase;
use League\FactoryMuffin\FactoryMuffin;

class LogTest extends TestCase
{
    protected static $fm;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_parse_log_entry_from_file()
    {
        config()->set('logviewer.log_files_directory', 'tests/dummy_data/storage/logs');

        $files = LogViewer::files();
        $exampleFile = $files[0];

        $logs = LogViewer::setFile($exampleFile)
            ->logs();

        $this->assertEquals(2, $logs->count());
    }

    /** @test */
    public function it_can_parse_log_entry_to_prototype()
    {
        config()->set('logviewer.log_files_directory', 'tests/dummy_data/storage/logs');

        $files = LogViewer::files();
        $exampleFile = $files[0];

        $log = LogViewer::setFile($exampleFile)
            ->logs()->first();

        $this->assertInstanceOf(Carbon::class, $log->date);
        $this->assertEquals($log->date, Carbon::parse('2021-01-01 20:00:02'));
        $this->assertSame('local', $log->logger);
        $this->assertSame('error', $log->level);
        $this->assertNotEmpty($log->message);
        $this->assertFalse($log->isNew());
    }

    /** @test */
    public function it_marks_log_as_new_when_created_within_last_10_minutes()
    {
        $fm = new FactoryMuffin();
        $fm->loadFactories('tests/factories');

        $log = $fm->instance(Log::class, [
           'date' => now()->subMinutes(9),
        ]);

        $this->assertTrue($log->isNew());
    }

    /** @test */
    public function it_marks_log_as_not_new_when_created_more_than_10_minutes_ago()
    {
        $fm = new FactoryMuffin();
        $fm->loadFactories('tests/factories');

        $log = $fm->instance(Log::class, [
           'date' => now()->subMinutes(11),
        ]);

        $this->assertFalse($log->isNew());
    }

    /** @test */
    public function it_excludes_all_logs_that_are_below_the_minimum_threshold()
    {
        config()->set('logviewer.log_files_directory', 'tests/dummy_data/storage/logs');

        $logs = LogViewer::setFile('laravel-2021-01-01.log')->logs();

        $this->assertEquals(2, $logs->count());

        config()->set('logviewer.minimum_level', LogViewerLevel::ERROR);

        $logs = LogViewer::logs();

        $this->assertEquals(1, $logs->count());
    }

    /** @test */
    public function it_throws_file_not_set_exception_when_not_set()
    {
        $this->expectException(LogFileException::class);

        LogViewer::setFile()->logs();
    }

    /** @test */
    public function it_throws_file_not_found_exception_when_file_not_found()
    {
        $this->expectException(LogFileException::class);

        LogViewer::setFile('tests/dummy_data/storage/logs/empty')->logs();
    }

    /** @test */
    public function it_throws_channel_not_supported_exception_when_invalid_channel_set()
    {
        $this->expectException(LogChannelException::class);

        LogViewer::setChannel('stack')->logs();
    }
}
