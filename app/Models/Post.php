<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $touches = ['author'];

    protected $guarded = [];

    protected $with = ['author'];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    /**
     * 获取封面图.
     */
    public function cover()
    {
        return $this->hasOne(Image::class, 'post_id', 'id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable', );
    }
}

// 出版了 书的内容包含 f 的作者
Author::whereHas('posts', function ($q) {$q->where('content', 'like', '%q%'); })->get();

Author::whereHas('posts', function ($query) {$query->where('posts.content', 'like', '%f%'); })->get();
