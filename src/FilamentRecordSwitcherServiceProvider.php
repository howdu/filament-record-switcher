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
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('howdu/filament-record-switcher');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Testing
        Testable::mixin(new TestsFilamentRecordSwitcher());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'howdu/filament-record-switcher';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentRecordSwitcherCommand::class,
        ];
    }
}
