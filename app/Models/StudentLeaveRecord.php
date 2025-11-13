<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLeaveRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'leave_type',
        'student_name',
        'date_from',
        'date_to',
        'parent_name',
        'parent_phone'
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
