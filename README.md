# Resource title navigation dropdown with search for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/howdu/filament-record-switcher.svg?style=flat-square)](https://packagist.org/packages/howdu/filament-record-switcher)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/howdu/filament-record-switcher/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/howdu/filament-record-switcher/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/howdu/filament-record-switcher.svg?style=flat-square)](https://packagist.org/packages/howdu/filament-record-switcher)

![Screenshot](https://github.com/howdu/filament-record-switcher/assets/533658/f0c62589-bd5f-4463-bf93-124b1c37955b)

Subtly convert the page title into a dropdown navigation that's displayed on click. 

It works similar to Filament's global search but only shows results for the current resource.

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
E.g `app/Filament/Resources/Category/Pages/EditCategory.php`
```php
use Howdu\FilamentRecordSwitcher\Filament\Concerns\HasRecordSwitcher;

class EditCategory extends EditRecord
{
    use HasRecordSwitcher;
}
```

Note: this trail will overwrite the `getHeading()` method if you've overwritten it in your Page you need to 
overwrite `getRecordTitle()` instead.

If you want to add custom logic to the `afterSave()` method, you can do so by using the `recordSwitcherAfterSave()` method from the trait.
```php
 use HasRecordSwitcher {
    afterSave as recordSwitcherAfterSave;
}

protected function afterSave(): void
{
    /// Custom logic

    $this->recordSwitcherAfterSave();
}
````


3. Check your Resource class e.g CategoryResource has the record title attribute set which's used as the label in the select dropdown.
```php
protected static ?string $recordTitleAttribute = 'title';
```

4. Finally publish plugin assets.
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
