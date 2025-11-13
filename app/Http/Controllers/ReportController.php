<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Record::with('dormitory');

        // Apply filters
        if ($request->filled('type')) {
            $query->where('record_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('report_status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function show(Record $record)
    {
        $record->load([
            'dormitory',
            'recordStudents',
            'foodRecord',
            'hospitalRecord',
            'repairRecord',
            'inventoryRecord',
            'permissionRecord',
            'studentLeaveRecord'
        ]);

        return view('reports.show', compact('record'));
    }
}
