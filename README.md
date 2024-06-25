# Resource search dropdown navigation for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/howdu/filament-record-switcher.svg?style=flat-square)](https://packagist.org/packages/howdu/filament-record-switcher)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/howdu/filament-record-switcher/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/howdu/filament-record-switcher/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/howdu/filament-record-switcher/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/howdu/filament-record-switcher/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/howdu/filament-record-switcher.svg?style=flat-square)](https://packagist.org/packages/howdu/filament-record-switcher)

Subtly convert the page title into a dropdown navigation that's displayed on click.  

It works similar to Filament's global search but only show results for the current resource.

![preview](https://github.com/howdu/filament-record-switcher/assets/533658/f0c62589-bd5f-4463-bf93-124b1c37955b)

## Installation

You can install the package via composer:

```bash
composer require howdu/filament-record-switcher
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-record-switcher-views"
```

## Usage

1. Register plugin inside your PanelProvider(s). 
E.g `app/Filament/Providers/AdminPanelProvider.php`
```php
use Howdu\FilamentRecordSwitcher\FilamentRecordSwitcherPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(
                FilamentRecordSwitcherPlugin::make(),
            );
    }
}
```
2. Add the `HasRecordSwitcher` trait to each of your edit record Page.
E.g `app/Filament/Resources/Category/EditCategory.php`
```php
use Howdu\FilamentRecordSwitcher\Filament\Concerns\HasRecordSwitcher;

class EditCategory extends EditRecord
{
    use HasRecordSwitcher;
}
```
3. Publish plugin assets.
```bash
php artisan filament:assets
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ben J](https://github.com/howdu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
