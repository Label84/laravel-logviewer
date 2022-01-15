# Laravel LogViewer

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-logviewer/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-logviewer)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/label84/laravel-logviewer.svg?style=flat-square)](https://scrutinizer-ci.com/g/label84/laravel-logviewer)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-logviewer.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-logviewer)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/label84/laravel-logviewer/run-tests?label=Tests&style=flat-square)

Laravel LogViewer enables you to view and filter your Laravel logs in the browser.

![LogViewer screenshot](./docs/screenshot_default.png?raw=true "LogViewer Screenshot")

- [Requirements](#requirements)
- [Laravel support](#laravel-support)
- [Installation](#installation)
- [Usage](#usage)
  - [Query filters](#query-filters)
  - [Settings](#settings)
  - [Examples](#examples)
- [Tests](#tests)
- [License](#license)

## Requirements

- Laravel 8.x or 9.x
- PHP >=7.4 or 8.x

## Laravel support

| Version | Release |
|---------|---------|
| 9.x     | ^2.1    |
| 8.x     | ^2.0    |
| 7.x     | ^1.0    |

## Limitations

This package only support logs created by the [Monolog PHP logging libary](https://seldaek.github.io/monolog). Laravel uses this libary by default for the 'single' and 'daily' channels. For more information see [Laravel - Logging](https://laravel.com/docs/8.x/logging).

## Installation

### 1. Require package

Add the package to your application.

```sh
composer require label84/laravel-logviewer
```

You can also manually update your composer.json.

### 2. Install package

Add the config file to your application.

```sh
php artisan logviewer:install

OR

php artisan vendor:publish --provider="Label84\LogViewer\LogViewerServiceProvider" --tag="config"
```

#### 2.1 Publish the views (optional)

To change the default views, you can publish the views to your application.

```sh
php artisan vendor:publish --provider="Label84\LogViewer\LogViewerServiceProvider" --tag="views"
```

## Usage

Visit the following url in your application: ``/admin/logviewer``

You can change the url in the config file.

### Query filters

You can filter the logs in the overview with query parameters - example ``/admin/logviewer?date=today&message=kiss``.

| Parameter     | Value                                    | Example           |
|:--------------|:-----------------------------------------|:------------------|
| level=        | string                                   | DEBUG             |
| date=         | [Carbon](https://carbon.nesbot.com/docs) | today             |
| from=         | [Carbon](https://carbon.nesbot.com/docs) | yesterday         |
| till=         | [Carbon](https://carbon.nesbot.com/docs) | 2021-01-01        |
| logger=       | string                                   | local             |
| message=      | string                                   | love              |

### Settings

To use the package in your own Controllers you can use the following settings. If you use the default package features and views you probably won't need this.

#### 1. Set channel

To dynamically set the channel:

```php
use LogViewer;

LogViewer::setChannel(string $channel);
```

#### 2. Set path

To dynamically set the path:

```php
use LogViewer;

LogViewer::setPath(string $path);
```

#### 3. Set file

To dynamically set the file:

```php
use LogViewer;

LogViewer::setFile(string $file);
```

#### 4. Available methods

The `LogViewerCollection` extends ``Illuminate\Support\Collection`` with the following methods:

- whereLevel(int|string $level)
- whereMinLevel(int|string $level)
- whereMaxLevel(int|string $level)
- whereDate(Carbon|string $date)
- whereDateFrom(Carbon|string $date)
- whereDateTill(Carbon|string $date)
- whereDateBetween(Carbon|string $startDate, Carbon|string $startDate)
- whereLogger(string|array $logger)
- whereMessage(string|array $query)
- whereNotMessage(string|array $query)
- whereUser(int $user)

### Examples

#### Example 1

List all logs.

```php
use LogViewer;

public function index(Request $request): View
{
    $items = LogViewer::logs()->paginate(50);

    return view('dashboard', compact('items'));
}
```

#### Example 2

List all logs created today with a minimum level of ERROR.

```php
use Label84\LogViewer\Support\LogViewerLevel;
use LogViewer;

public function index(Request $request): View
{
    $items = LogViewer::logs()
        ->whereDate(today())
        ->whereMinLevel(LogViewerLevel::ERROR)
        ->paginate(50);

    return view('dashboard', compact('items'));
}
```

#### Example 3

List all logs with a minium level of DEBUG that contains the words 'Foo' and/or 'Bar'.

```php
use Label84\LogViewer\Support\LogViewerLevel;
use LogViewer;

public function index(Request $request): View
{
    $items = LogViewer::logs()
        ->whereLevel(LogViewerLevel::DEBUG)
        ->whereMessage(['Foo', 'Bar'])
        ->paginate(50);

    return view('dashboard', compact('items'));
}
```

#### Levels

| Level         |     |
|:--------------|-----|
| Emergency     | 600 |
| Alert         | 550 |
| Critical      | 500 |
| Error         | 400 |
| Warning       | 300 |
| Notice        | 250 |
| Info          | 200 |
| Debug         | 100 |

## Tests

```sh
composer analyse
composer test
```

## License

[MIT](https://opensource.org/licenses/MIT)
