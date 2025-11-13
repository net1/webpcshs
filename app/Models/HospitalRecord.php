<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HospitalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'status',
        'hospital_name',
        'symptoms'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }
}
