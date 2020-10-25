<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public function __construct($table = null)
    {
        if (!is_null($table)) {
            $this->table = $table;
        }
    }

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id', 'justtest');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_author', 'author_id', 'role_id', 'id', 'id', );
        // return $this->belongsToMany('App\Models\Role', 'role_author', 'author_id', 'role_id', 'id', 'id', );
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
