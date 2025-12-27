<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'activity',
        'score',
        'log_date',
        'log_time',
    ];

    protected $casts = [
        'log_date' => 'date',
        'log_time' => 'datetime:H:i',
    ];
}
