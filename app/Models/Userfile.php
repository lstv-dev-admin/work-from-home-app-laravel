<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userfile extends Model
{
    use HasFactory;

    protected $table = 'userfile';
    protected $primaryKey = 'usrcde';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'usrcde',
        'usrpwd',
        'monitorsetup',
    ];
}

