# Laravel LogViewer

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-logviewer/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-logviewer)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/label84/laravel-logviewer.svg?style=flat-square)](https://scrutinizer-ci.com/g/label84/laravel-logviewer)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-logviewer.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-logviewer)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/label84/laravel-logviewer/run-tests?label=Tests&style=flat-square)

Laravel LogViewer enables you to view and filter through the logs of your Laravel application.

![LogViewer screenshot](./docs/screenshot_default.png?raw=true "LogViewer Screenshot")

- [Requirements](#requirements)
- [Limitations](#limitations)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Notes](#Notes)
- [Tests](#tests)
- [Security](#security)
- [License](#license)

## Requirements

- Laravel 7.x or 8.x
- PHP >= 7.4 or 8.0

## Laravel support

| Version       | Release       |
|:--------------|:-------------:|
| 7.x to 8.x    | 1.0           |

## Limitations

The package only support logs created by the [Monolog PHP logging libary](https://seldaek.github.io/monolog). 

Laravel use this libary by default for the 'single' and 'daily' channels. For more information see [Laravel - Logging](https://laravel.com/docs/8.x/logging).

## Installation

### 1. Require package

Add the package to your application:

```sh
composer require label84/laravel-logviewer
```

You can also do this manually by updating your composer.json file.

### 2. Install package

Add the config to your application:

```sh
php artisan logviewer:install
```

#### 2.1 Install package manually (alternative)

You can also install the package manually by executing the following command:

```sh
php artisan vendor:publish Label84\LogViewer\LogViewerServiceProvider --config
```

#### 2.2 Publish the views (optional)

To change the default views, you can publish them to your application with the following command:

```sh
php artisan vendor:publish Label84\LogViewer\LogViewerServiceProvider --views
```

## Usage

To preview the logs of your application visit: /admin/logviewer

You can change the route in the config file.

### Filter the logs

You can filter the listed logs in the overview with query parameters (the ?foo=bar ones).

| Parameter     | Value                                    | Example           |
|:--------------|:-----------------------------------------|:------------------|
| level=        | string                                   | DEBUG             |
| date=         | [Carbon](https://carbon.nesbot.com/docs) | today             |
| from=         | [Carbon](https://carbon.nesbot.com/docs) | yesterday         |
| till=         | [Carbon](https://carbon.nesbot.com/docs) | 2021-01-01        |
| logger=       | string                                   | local             |
| message=      | string                                   | love              |

## Configuration

### Settings

By default the LogViewer will use the path/channel set in the config file.

Laravel uses the 'single' channel as default log channel (channel defined in stack) and in that case you don't have to configure anything. If your application uses the 'daily' channel you can you have to change the channel to 'daily' in the config file.

To change the directory and/or file, change the following to values in the config file:

- log_files_directory
- log_channel

#### Set log dynamically

To set the log path/file/channel dynamically:

##### 1. Set channel

To dynamically set the channel:

```php
LogViewer::setChannel(string $channel);
```

##### 2. Set path

To dynamically set the path:

```php
LogViewer::setPath(string $path);
```

##### 3. Set file

To dynamically set the file:

```php
LogViewer::setFile(string $file);
```

### LogViewer collection

The package also has a `LogViewerCollection` class that extends the [Laravel collection](https://laravel.com/docs/8.x/collections).

The LogViewerCollection is an convenient wrapper for the records in the log file and allows you to easily filter them.

```php
use LogViewer;

public function index(Request $request): View
{
    $items = LogViewer::logs()->paginate(50);

    return view('yourView', compact('items'));
}
```

#### Show all logs created today with level ERROR or higher

```php
use Label84\LogViewer\Support\LogViewerLevel;
use LogViewer;

public function index(Request $request): View
{
    $items = LogViewer::logs()
        ->whereDate(today())
        ->whereMinLevel(LogViewerLevel::ERROR)
        ->paginate(50);

    return view('yourView', compact('items'));
}
```

#### Show all logs of level DEBUG where message contains 'ABC' or 'DEF'

```php
use Label84\LogViewer\Support\LogViewerLevel;
use LogViewer;

public function index(Request $request): View
{
    $items = LogViewer::logs()
        ->whereLevel(LogViewerLevel::DEBUG)
        ->whereMessage(['ABC', 'DEF'])
        ->paginate(50);

    return view('yourView', compact('items'));
}
```

#### Collection methods

The `LogViewerCollection` extends the standard Laravel collection. Therefore you can still use all other available methods of the collection class.

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

#### Levels

In the `LogViewerLevel` class you can find all levels.

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

## Notes

- Bootstrap 5 is used for the default interface
- Middleware [web] + [auth] are applied by default

## Tests

```sh
./vendor/bin/phpunit
```

## Security

By default the package only applies the [web] and [auth] middleware to the LogViewer routes. When used in production make sure you apply extra middleware or other security measure to prevent unwanted usage.

## License

[MIT](https://opensource.org/licenses/MIT)
