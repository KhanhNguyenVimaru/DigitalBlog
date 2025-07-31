<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(\App\Models\category::class, 'categoryId');
    }

    public function long_content()
    {
        return $this->hasMany(\App\Models\long_content::class, 'postId');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'authorId');
    }
    public function comment(){
        return $this->hasMany(\App\Models\comment::class, 'post_id');
    }
    public function like(){
        return $this->hasMany(\App\Models\comment::class, 'post_id');
    }
}
