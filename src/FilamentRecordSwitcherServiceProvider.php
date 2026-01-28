<?php

namespace Howdu\FilamentRecordSwitcher;

use Howdu\FilamentRecordSwitcher\Commands\FilamentRecordSwitcherCommand;
use Howdu\FilamentRecordSwitcher\Testing\TestsFilamentRecordSwitcher;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentRecordSwitcherServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-record-switcher';

    public static string $viewNamespace = 'filament-record-switcher';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews(static::$viewNamespace)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('howdu/filament-record-switcher');
            });
    }
}
