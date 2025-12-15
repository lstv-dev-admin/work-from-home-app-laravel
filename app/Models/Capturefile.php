<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capturefile extends Model
{
    use HasFactory;

    protected $table = 'capturefile';
    protected $primaryKey = 'recid';
    public $timestamps = false;

    protected $fillable = [
        'empcde',
        'scimagename',
        'scdirectoryname',
        'screason',
        'snimagename',
        'sndirectoryname',
        'snreason',
        'capdate',
        'captime',
    ];

    protected $casts = [
        'capdate' => 'date',
    ];
}

