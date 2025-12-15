<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tablepar extends Model
{
    use HasFactory;

    protected $table = 'tablepar';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'empcde',
        'gridorder',
        'usrcde',
    ];
}

