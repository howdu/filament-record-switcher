<?php

namespace Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\Resources\Post\Pages;

use Filament\Resources\Pages\EditRecord;
use Howdu\FilamentRecordSwitcher\Filament\Concerns\HasRecordSwitcher;
use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\Resources\Post\PostResource;

class EditPost extends EditRecord
{
    use HasRecordSwitcher;

    protected static string $resource = PostResource::class;
}
