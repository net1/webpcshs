<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'subject',
        'purpose',
        'date_from',
        'date_to'
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }
}
