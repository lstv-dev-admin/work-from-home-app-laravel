<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syspar extends Model
{
    use HasFactory;

    protected $table = 'syspar';
    protected $primaryKey = 'recid';
    public $timestamps = false;

    protected $fillable = [
        'interval',
        'progver',
    ];
}

