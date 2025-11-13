@extends('layouts.app')

@section('title', 'รายงาน - ' . config('app.name'))

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 รายงานทั้งหมด</h2>

    <!-- Filters -->
    <form method="GET" class="mb-4 flex gap-4 flex-wrap">
        <select name="type" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกประเภท</option>
            @foreach(\App\Models\Record::getTypeLabels() as $key => $label)
                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกสถานะ</option>
            <option value="รอรับรองรายงาน" {{ request('status') == 'รอรับรองรายงาน' ? 'selected' : '' }}>รอรับรองรายงาน</option>
            <option value="รับรองรายงาน" {{ request('status') == 'รับรองรายงาน' ? 'selected' : '' }}>รับรองรายงาน</option>
            <option value="ไม่รับรองรายงาน" {{ request('status') == 'ไม่รับรองรายงาน' ? 'selected' : '' }}>ไม่รับรองรายงาน</option>
        </select>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">🔍 กรอง</button>
        <a href="{{ route('reports') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">ล้างตัวกรอง</a>
    </form>

    <!-- Reports List -->
    <div class="space-y-3">
        @forelse($reports as $report)
            @php
                $color = $report->report_status === 'รับรองรายงาน' ? 'green' : ($report->report_status === 'ไม่รับรองรายงาน' ? 'red' : 'yellow');
            @endphp
            <div class="border-l-4 border-{{ $color }}-500 bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-start flex-wrap gap-2">
                    <div class="flex-1">
                        <div class="font-bold text-gray-800">
                            {{ $report->type_label }} - {{ $report->dormitory->name ?? 'ทั่วไป' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $report->record_date->format('d/m/Y') }} | บันทึกโดย: {{ $report->recorder }}
                        </div>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 text-sm rounded-full bg-{{ $color }}-100 text-{{ $color }}-700">
                                {{ $report->report_status }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('reports.show', $report) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm whitespace-nowrap">
                        👁️ ดูรายงาน
                    </a>
                </div>
                @if($report->details)
                    <div class="text-sm text-gray-600 mt-2">
                        {{ Str::limit($report->details, 150) }}
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">ไม่พบรายงาน</p>
        @endforelse
    </div>
</div>
@endsection
