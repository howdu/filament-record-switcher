<?php

use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Filament\Resources\Post\Pages\EditPost;
use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Models\Post;
use function Pest\Livewire\livewire;

it('can render the edit page', function (): void {
    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->id])
        ->assertOk()
        ->assertElementExists('.filament-record-switcher');
});

it('dispatches refresh event when saving', function (): void {
    $post = Post::factory()->create();

    livewire(EditPost::class, ['record' => $post->id])
        ->assertOk()
        ->call('save')
        ->assertDispatched('record-switcher:refresh');
});

it('show record switcher options', function (): void {
    $posts = Post::factory()->count(6)->create();
    $post = $posts->random();

    livewire(EditPost::class, ['record' => $post->id])
        ->assertOk()
        ->call('getRecordSwitcherOptions')
        ->assertReturned(function (array $options): bool {
            expect($options)->toHaveCount(6);

            return true;
        });
});
