<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function authors()
    {
        return $this->hasMany(Author::class, 'company_id', 'id');
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, Author::class, 'company_id', 'author_id', 'id', 'id');
    }
}
