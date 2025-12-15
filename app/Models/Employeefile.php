<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employeefile extends Model
{
    use HasFactory;

    protected $table = 'employeefile';
    protected $primaryKey = 'empcde';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'empcde',
        'imglocation',
        'ess_empcode',
    ];

    public function screenshots(): HasMany
    {
        return $this->hasMany(Screenshotfile::class, 'empcde', 'empcde');
    }
}

