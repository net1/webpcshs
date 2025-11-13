<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_type',
        'record_date',
        'dormitory_id',
        'details',
        'recorder',
        'report_status',
        'approval_name',
        'approval_details',
        'approval_date',
        'approval_timestamp'
    ];

    protected $casts = [
        'record_date' => 'date',
        'approval_date' => 'date',
        'approval_timestamp' => 'datetime'
    ];

    public function dormitory(): BelongsTo
    {
        return $this->belongsTo(Dormitory::class);
    }

    public function recordStudents(): HasMany
    {
        return $this->hasMany(RecordStudent::class);
    }

    public function foodRecord(): HasOne
    {
        return $this->hasOne(FoodRecord::class);
    }

    public function hospitalRecord(): HasOne
    {
        return $this->hasOne(HospitalRecord::class);
    }

    public function repairRecord(): HasOne
    {
        return $this->hasOne(RepairRecord::class);
    }

    public function inventoryRecord(): HasOne
    {
        return $this->hasOne(InventoryRecord::class);
    }

    public function permissionRecord(): HasOne
    {
        return $this->hasOne(PermissionRecord::class);
    }

    public function studentLeaveRecord(): HasOne
    {
        return $this->hasOne(StudentLeaveRecord::class);
    }

    public static function getTypeLabels(): array
    {
        return [
            'dormitory' => 'เข้าออกหอพัก',
            'food-stock' => 'รับอาหารเสริม',
            'food-distribute' => 'จ่ายอาหารเสริม',
            'hospital' => 'โรงพยาบาล',
            'uniform' => 'เครื่องแต่งกาย',
            'duty' => 'ปฏิบัติหน้าที่',
            'repair' => 'แจ้งซ่อม',
            'inventory-receive' => 'รับวัสดุ',
            'inventory-issue' => 'จ่ายวัสดุ',
            'permission' => 'ขออนุญาต',
            'student-leave' => 'ขออนุญาตลา'
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::getTypeLabels()[$this->record_type] ?? 'อื่นๆ';
    }
}
