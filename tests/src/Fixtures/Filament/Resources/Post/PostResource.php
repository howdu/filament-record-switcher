<?php

namespace Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\Resources\Post;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Models\Post;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
