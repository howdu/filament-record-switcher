<?php

namespace Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\Resources\Post\Pages;

use Filament\Resources\Pages\ListRecords;
use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\Resources\Post\PostResource;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;
}
