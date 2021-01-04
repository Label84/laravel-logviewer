<?php

namespace Label84\LogViewer\Tests\Support;

use Label84\LogViewer\Prototypes\Log;
use Label84\LogViewer\Support\LogViewerCollection;
use Label84\LogViewer\Support\LogViewerLevel;
use Label84\LogViewer\Tests\TestCase;
use League\FactoryMuffin\FactoryMuffin;

class LogViewerCollectionTest extends TestCase
{
    protected FactoryMuffin $fm;
    protected LogViewerCollection $collection;

    public function setUp(): void
    {
        parent::setUp();

        $this->fm = new FactoryMuffin();
        $this->fm->loadFactories('tests/factories');

        $this->collection = new LogViewerCollection($this->fm->seed(20, Log::class, [], false));
    }

    /** @test */
    public function it_has_20_items_in_collection()
    {
        $this->assertEquals(20, $this->collection->count());
    }

    /** @test */
    public function it_can_filter_collection_where_level()
    {
        $array1 = $this->fm->seed(15, Log::class, ['level' => 'DEBUG'], false);
        $array2 = $this->fm->seed(5, Log::class, ['level' => 'ERROR'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(5, $mergedCollection->whereLevel('ERROR')->count());
        $this->assertEquals(5, $mergedCollection->whereLevel(LogViewerLevel::ERROR)->count());
    }

    /** @test */
    public function it_can_filter_collection_where_min_level()
    {
        $array1 = $this->fm->seed(15, Log::class, ['level' => 'DEBUG'], false);
        $array2 = $this->fm->seed(5, Log::class, ['level' => 'ERROR'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(5, $mergedCollection->whereMinLevel('ERROR')->count());
        $this->assertEquals(20, $mergedCollection->whereMinLevel('DEBUG')->count());
    }

    /** @test */
    public function it_can_filter_collection_where_max_level()
    {
        $array1 = $this->fm->seed(15, Log::class, ['level' => 'DEBUG'], false);
        $array2 = $this->fm->seed(5, Log::class, ['level' => 'ERROR'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(20, $mergedCollection->whereMaxLevel('ERROR')->count());
        $this->assertEquals(15, $mergedCollection->whereMaxLevel('DEBUG')->count());
    }

    /** @test */
    public function it_can_filter_collection_where_date()
    {
        $array1 = $this->fm->seed(15, Log::class, ['date' => today()], false);
        $array2 = $this->fm->seed(5, Log::class, ['date' => today()->subDay()], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(15, $mergedCollection->whereDate(today())->count());
        $this->assertEquals(15, $mergedCollection->whereDate(today()->format('Y-m-d'))->count());
    }

    /** @test */
    public function it_can_filter_collection_where_date_between()
    {
        $array1 = $this->fm->seed(5, Log::class, ['date' => today()], false);
        $array2 = $this->fm->seed(5, Log::class, ['date' => today()->subDay()], false);
        $array3 = $this->fm->seed(5, Log::class, ['date' => today()->subDays(2)], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2, $array3));

        $this->assertEquals(10, $mergedCollection->whereDateBetween(today()->subDay(), today())->count());
        $this->assertEquals(10, $mergedCollection->whereDateBetween(
                today()->subDay()->format('Y-m-d'),
                today()->format('Y-m-d')
            )->count());
    }

    /** @test */
    public function it_can_filter_collection_where_logger()
    {
        $array1 = $this->fm->seed(15, Log::class, ['logger' => 'local'], false);
        $array2 = $this->fm->seed(5, Log::class, ['logger' => 'dummy'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(15, $mergedCollection->whereLogger('local')->count());
    }

    /** @test */
    public function it_can_filter_collection_where_message()
    {
        $array1 = $this->fm->seed(15, Log::class, ['message' => 'ABCDEF'], false);
        $array2 = $this->fm->seed(5, Log::class, ['message' => '123456'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(15, $mergedCollection->whereMessage('ABC')->count());
        $this->assertEquals(20, $mergedCollection->whereMessage(['ABC', '123'])->count());
    }

    /** @test */
    public function it_can_filter_collection_where_message_not()
    {
        $array1 = $this->fm->seed(15, Log::class, ['message' => 'ABCDEF'], false);
        $array2 = $this->fm->seed(5, Log::class, ['message' => '123456'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(5, $mergedCollection->whereNotMessage('ABC')->count());
        $this->assertEquals(0, $mergedCollection->whereNotMessage(['ABC', '123'])->count());
    }

    /** @test */
    public function it_can_filter_collection_where_user()
    {
        $array1 = $this->fm->seed(15, Log::class, ['message' => 'ABCDEF {"userId":1,"exception":"[object]...'], false);
        $array2 = $this->fm->seed(5, Log::class, ['message' => 'ABCDEF {"userId":2,"exception":"[object]...'], false);

        $mergedCollection = new LogViewerCollection(array_merge($array1, $array2));

        $this->assertEquals(15, $mergedCollection->whereUser(1)->count());
        $this->assertEquals(5, $mergedCollection->whereUser(2)->count());
        $this->assertEquals(0, $mergedCollection->whereUser(3)->count());
    }
}
