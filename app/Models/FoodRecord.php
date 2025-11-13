<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'food_type',
        'transaction_type',
        'quantity'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }
}
