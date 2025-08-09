<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    /** @use HasFactory<\Database\Factories\NotifyFactory> */
    use HasFactory;
    protected $fillable = [
        'send_from_id',
        'send_to_id',
        'type',
        'notify_content',
        'addition'
    ];
}
