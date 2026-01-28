<?php

namespace Howdu\FilamentRecordSwitcher\Tests\Database\Factories;

use Howdu\FilamentRecordSwitcher\Tests\Fixtures\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'slug' => Str::slug($this->faker->sentence(4)),
            'title' => $this->faker->sentence(4),
            'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
        ];
    }
}
