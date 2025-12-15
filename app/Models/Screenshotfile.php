<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Screenshotfile extends Model
{
    use HasFactory;

    protected $table = 'screenshotfile';
    protected $primaryKey = 'recid';
    public $timestamps = false;

    protected $fillable = [
        'empcde',
        'directoryname',
        'imagename',
        'date',
        'time',
        'trankey',
        'tranrun',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employeefile::class, 'empcde', 'empcde');
    }
}

