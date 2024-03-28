[![Latest Version on Packagist](https://img.shields.io/packagist/v/amendozaaguiar/filament-route-statistics.svg?style=flat-square)](https://packagist.org/packages/amendozaaguiar/filament-route-statistics)
[![Total Downloads](https://img.shields.io/packagist/dt/amendozaaguiar/filament-route-statistics.svg?style=flat-square)](https://packagist.org/packages/amendozaaguiar/filament-route-statistics)

# Filament Route Statistics

A Filament plugin for [Laravel Route Statistics](https://github.com/bilfeldt/laravel-route-statistics) package.

This package provides a Filament page for [Laravel Route Statistics](https://github.com/bilfeldt/laravel-route-statistics).

## Requirements

- PHP 8.1 | 8.2
- [Filament 3](https://github.com/laravel-filament/filament)

## Dependencies

- [bilfeldt/laravel-route-statistics](https://github.com/bilfeldt/laravel-route-statistics)

## Installation

You can install the plugin via Composer:

```bash
composer require amendozaaguiar/filament-route-statistics
```

You can publish the translations files with:

```bash
php artisan vendor:publish --tag="filament-route-statistics-translations"
```

## Using the Page

Add this plugin to a panel on `plugins()` method.
E.g. in `app/Providers/Filament/AdminPanelProvider.php`:

```php
use Amendozaaguiar\FilamentRouteStatistics\FilamentRouteStatisticsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentRouteStatisticsPlugin::make(),
            //...
        ]);
}
```

That's it! Now you can see the log page on left sidebar.
