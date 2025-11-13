<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'item_name',
        'category',
        'transaction_type',
        'quantity',
        'unit',
        'issued_to'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }
}
