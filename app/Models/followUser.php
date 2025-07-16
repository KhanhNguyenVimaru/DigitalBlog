<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class followUser extends Model
{
    use HasFactory;

    public function follower()
    {
        return $this->belongsTo(User::class, 'followerId');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'authorId');
    }
}