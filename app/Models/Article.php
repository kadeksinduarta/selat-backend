<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'author',
        'category',
        'published_at'
    ];

    // otomatis generate slug ketika create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);
        });

        static::updating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
