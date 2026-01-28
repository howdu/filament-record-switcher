<?php

namespace Howdu\FilamentRecordSwitcher\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Howdu\FilamentRecordSwitcher\FilamentRecordSwitcherServiceProvider;
use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\TestPanelProvider;
use Illuminate\Foundation\Auth\User;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Sinnbeck\DomAssertions\DomAssertionsServiceProvider;

use function Pest\Laravel\actingAs;

#[WithMigration]
class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            DomAssertionsServiceProvider::class,
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            SchemasServiceProvider::class,
            FilamentRecordSwitcherServiceProvider::class,
            TestPanelProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function login(?User $as = null): User
    {
        /** @var User */
        $user = $as ?? User::factory()->create();

        actingAs($user);

        return $user;
    }
}
