<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Student;
use App\Models\Dormitory;
use App\Models\FoodRecord;
use App\Models\RepairRecord;
use App\Models\InventoryRecord;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Main statistics
        $stats = [
            'dormitory_count' => Record::where('record_type', 'dormitory')->count(),
            'food_count' => Record::whereIn('record_type', ['food-stock', 'food-distribute'])->count(),
            'hospital_count' => Record::where('record_type', 'hospital')->count(),
            'total_count' => Record::count(),
            'student_count' => Student::count(),
            'dormitory_list_count' => Dormitory::count()
        ];

        // Food stock calculation
        $milkStock = FoodRecord::where('food_type', 'นม')
            ->selectRaw('SUM(CASE WHEN transaction_type = "รับ" THEN quantity ELSE -quantity END) as stock')
            ->value('stock') ?? 0;

        $breadStock = FoodRecord::where('food_type', 'ขนมปัง')
            ->selectRaw('SUM(CASE WHEN transaction_type = "รับ" THEN quantity ELSE -quantity END) as stock')
            ->value('stock') ?? 0;

        // Inventory items count
        $inventoryCount = InventoryRecord::selectRaw('item_name, category, SUM(CASE WHEN transaction_type = "รับ" THEN quantity ELSE -quantity END) as stock')
            ->groupBy('item_name', 'category')
            ->having('stock', '>', 0)
            ->count();

        // Detailed stats for each type
        $detailedStats = [
            'dormitory' => Record::where('record_type', 'dormitory')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'food' => Record::whereIn('record_type', ['food-stock', 'food-distribute'])
                ->count(),
            'hospital' => Record::where('record_type', 'hospital')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'uniform' => Record::where('record_type', 'uniform')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'duty' => Record::where('record_type', 'duty')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'repair' => Record::where('record_type', 'repair')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'inventory' => Record::whereIn('record_type', ['inventory-receive', 'inventory-issue'])
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'permission' => Record::where('record_type', 'permission')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
            'student-leave' => Record::where('record_type', 'student-leave')
                ->selectRaw('COUNT(*) as count, MAX(record_date) as latest_date')
                ->first(),
        ];

        // Repair pending count
        $repairPending = RepairRecord::where('repair_status', 'รอดำเนินการ')->count();

        // Recent records
        $recentRecords = Record::with('dormitory')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'milkStock',
            'breadStock',
            'inventoryCount',
            'detailedStats',
            'repairPending',
            'recentRecords'
        ));
    }
}
