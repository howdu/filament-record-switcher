<?php

namespace Howdu\FilamentRecordSwitcher;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class FilamentRecordSwitcherPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-record-switcher';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            AlpineComponent::make(
                'record-switcher',
                __DIR__ . '/../resources/dist/filament-record-switcher.js'
            )->loadedOnRequest(),
            Css::make('record-switcher', __DIR__ . '/../resources/dist/filament-record-switcher.css')->loadedOnRequest(),
        ], package: 'howdu/filament-record-switcher');
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
