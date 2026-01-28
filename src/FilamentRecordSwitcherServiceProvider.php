<?php

namespace Howdu\FilamentRecordSwitcher;

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
