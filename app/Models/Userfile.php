<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Userfile extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'userfile';
    protected $primaryKey = 'usrcde';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true; // Now we have timestamps

    protected $fillable = [
        'usrcde',
        'email',
        'name',
        'usrpwd',
        'monitorsetup',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'usrpwd',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}

