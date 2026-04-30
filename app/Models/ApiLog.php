<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'method',
        'url',
        'headers',
        'body',
        'response_status',
    ];

    protected $casts = [
        'headers' => 'array',
        'body' => 'array',
    ];
}
