<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daytran extends Model
{
    use HasFactory;

    protected $table = 'daytran';
    protected $primaryKey = 'recid';
    public $timestamps = false;

    protected $fillable = [
        'empcde',
        'time',
        'date',
        'type',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}

