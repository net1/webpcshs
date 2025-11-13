<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'location',
        'category',
        'problem',
        'urgency',
        'repair_status'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }
}
