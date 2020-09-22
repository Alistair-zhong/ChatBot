<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'role_author', 'role_id', 'author_id', 'id', 'id')
                ->using(RoleAuthor::class)
                ->withPivot(['created_at', 'updated_at']);
    }
}
