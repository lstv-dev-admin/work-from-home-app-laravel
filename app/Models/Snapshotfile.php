<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snapshotfile extends Model
{
    use HasFactory;

    protected $table = 'snapshotfile';
    protected $primaryKey = 'recid';
    public $timestamps = false;

    protected $fillable = [
        'empcde',
        'imagename',
        'directoryname',
        'date',
        'time',
        'trankey',
        'tranrun',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}

