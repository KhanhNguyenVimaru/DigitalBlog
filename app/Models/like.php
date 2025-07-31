<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'like',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function post(){
        return $this->belongsTo(post::class, 'post_id');
    }
}
