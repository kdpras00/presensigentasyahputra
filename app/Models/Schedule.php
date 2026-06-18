<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'day',
        'start_time',
        'late_time',
        'checkout_start_time',
        'end_time',
        'afternoon_start_time',
        'afternoon_late_time',
        'afternoon_checkout_start_time',
        'afternoon_end_time',
        'is_off',
    ];

    protected $casts = [
        'is_off' => 'boolean',
    ];
}
