<?php

namespace Label84\LogViewer\Tests\Factories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Label84\LogViewer\Prototypes\Log;
use Label84\LogViewer\Support\LogViewerLevel;
use ReflectionClass;
use ReflectionProperty;

$faker = \Faker\Factory::create();

$date = Carbon::parse($faker->dateTimeThisMonth);
$logger = $faker->randomElement(['local', 'production']);
$level = Str::upper($faker->randomElement(array_keys(LogViewerLevel::levels())));

$fm->define(Log::class)->setMaker(function () use ($date, $logger, $level) {
    $log = (new ReflectionClass(Log::class))->newInstanceWithoutConstructor();

    $property = new ReflectionProperty(Log::class, 'date');
    $property->setValue($log, $date);

    $property = new ReflectionProperty(Log::class, 'logger');
    $property->setValue($log, $logger);

    $property = new ReflectionProperty(Log::class, 'level');
    $property->setValue($log, $level);

    $message = 'SQLSTATE[HY000] [1049] Unknown database \'logviewer_demo_1\' (SQL: select * from `sessions` where `id` = UXFGu3QIF2WJCQNFv9KJZ4bReVYt1nd9X1GIFLW8 limit 1) {"exception":"[object] (Illuminate\\Database\\QueryException(code: 1049): SQLSTATE[HY000] [1049] Unknown database \'logviewer_demo_1\' (SQL: select * from `sessions` where `id` = UXFGu3QIF2WJCQNFv9KJZ4bReVYt1nd9X1GIFLW8 limit 1) at C:\\Users\\X\\Documents\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:669)';
    $property->setValue($log, $message);

    $property = new ReflectionProperty(Log::class, 'context');
    $property->setValue($log, '');

    $property = new ReflectionProperty(Log::class, 'extra');
    $property->setValue($log, '');

    $heading = "[{$date->format('Y-m-d H:i:s')}] {$logger}.{$level}: SQLSTATE[HY000] [1049] Unknown database 'logviewer_demo_1' (SQL: select * from `sessions` where `id` = UXFGu3QIF2WJCQNFv9KJZ4bReVYt1nd9X1GIFLW8 limit 1) {\"exception\":\"[object] (Illuminate\\Database\\QueryException(code: 1049): SQLSTATE[HY000] [1049] Unknown database 'logviewer_demo_1' (SQL: select * from `sessions` where `id` = UXFGu3QIF2WJCQNFv9KJZ4bReVYt1nd9X1GIFLW8 limit 1) at C:\\Users\\X\\Documents\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:669)";
    $property = new ReflectionProperty(Log::class, 'heading');
    $property->setAccessible(true);
    $property->setValue($log, $heading);

    $stack = '[stacktrace]
        #0 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(629): Illuminate\\Database\\Connection->runQueryCallback(\'select * from `...\', Array, Object(Closure))
        #1 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(338): Illuminate\\Database\\Connection->run(\'select * from `...\', Array, Object(Closure))
        #2 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(2149): Illuminate\\Database\\Connection->select(\'select * from `...\', Array, true)
        #3 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(2137): Illuminate\\Database\\Query\\Builder->runSelect()
        #4 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(2609): Illuminate\\Database\\Query\\Builder->Illuminate\\Database\\Query\\{closure}()
        #5 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(2138): Illuminate\\Database\\Query\\Builder->onceWithColumns(Array, Object(Closure))
        #6 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Concerns\\BuildsQueries.php(143): Illuminate\\Database\\Query\\Builder->get(Array)
        #7 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(2112): Illuminate\\Database\\Query\\Builder->first(Array)
        #8 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\DatabaseSessionHandler.php(91): Illuminate\\Database\\Query\\Builder->find(\'UXFGu3QIF2WJCQN...\')
        #9 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Store.php(97): Illuminate\\Session\\DatabaseSessionHandler->read(\'UXFGu3QIF2WJCQN...\')
        #10 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Store.php(87): Illuminate\\Session\\Store->readFromHandler()
        #11 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Store.php(71): Illuminate\\Session\\Store->loadSession()
        #12 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php(81): Illuminate\\Session\\Store->start()
        #13 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(422): Illuminate\\Session\\Middleware\\StartSession->Illuminate\\Session\\Middleware\\{closure}(Object(Illuminate\\Session\\Store))
        #14 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php(82): tap(Object(Illuminate\\Session\\Store), Object(Closure))
        #15 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Session\\Middleware\\StartSession.php(51): Illuminate\\Session\\Middleware\\StartSession->startSession(Object(Illuminate\\Http\\Request))
        #16 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Session\\Middleware\\StartSession->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #17 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse.php(37): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #18 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #19 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Cookie\\Middleware\\EncryptCookies.php(66): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #20 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #21 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #22 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(683): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
        #23 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(658): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))
        #24 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(624): Illuminate\\Routing\\Router->runRoute(Object(Illuminate\\Http\\Request), Object(Illuminate\\Routing\\Route))
        #25 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Routing\\Router.php(613): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))
        #26 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(170): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))
        #27 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(130): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))
        #28 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\spatie\\laravel-cookie-consent\\src\\CookieConsentMiddleware.php(12): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #29 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Spatie\\CookieConsent\\CookieConsentMiddleware->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #30 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\fideloper\\proxy\\src\\TrustProxies.php(57): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #31 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Fideloper\\Proxy\\TrustProxies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #32 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #33 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #34 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #35 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #36 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize.php(27): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #37 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #38 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode.php(63): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #39 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #40 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\barryvdh\\laravel-cors\\src\\HandlePreflight.php(29): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #41 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(171): Barryvdh\\Cors\\HandlePreflight->handle(Object(Illuminate\\Http\\Request), Object(Closure))
        #42 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(105): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
        #43 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(145): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
        #44 C:\\Users\\X\\Laravel\\LogViewer\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Http\\Kernel.php(110): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))
        #45 C:\\Users\\X\\Laravel\\LogViewer\\public\\index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))
        #46 {main}';
    $property = new ReflectionProperty(Log::class, 'stack');
    $property->setAccessible(true);
    $property->setValue($log, $stack);

    $property = new ReflectionProperty(Log::class, 'path');
    $property->setValue($log, 'tests/dummy/storage/logs/laravel.log');

    return $log;
});
