<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;
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
        return $this->hasMany(Comment::class, 'post_id');
    }
    public function likes(){
        return $this->hasMany(\App\Models\like::class, 'post_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'authorId');
    }
}
