<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'student_id',
        'student_name',
        'status',
        'reason'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
