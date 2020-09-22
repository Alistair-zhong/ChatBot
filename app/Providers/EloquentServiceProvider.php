<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Author;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Relation::morphMap([
            'posts' => Post::class,
            'authors' => Author::class,
        ]);
    }
}
