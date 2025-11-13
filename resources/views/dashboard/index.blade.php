@extends('layouts.app')

@section('title', 'หน้าหลัก - ' . config('app.name'))

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 slide-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกเข้าออกหอพัก</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['dormitory_count'] }}</p>
                </div>
                <div class="text-4xl">🏠</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 slide-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกอาหารเสริม</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['food_count'] }}</p>
                </div>
                <div class="text-4xl">🥛</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 slide-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกเข้าโรงพยาบาล</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['hospital_count'] }}</p>
                </div>
                <div class="text-4xl">🏥</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 slide-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_count'] }}</p>
                </div>
                <div class="text-4xl">📊</div>
            </div>
        </div>
    </div>

    <!-- Information Dashboard -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">📊 สารสนเทศการบันทึกทั้งหมด</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Dormitory Info -->
            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🏠</span>
                    <h4 class="text-lg font-bold text-gray-800">เข้าออกหอพัก</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-blue-600">{{ $detailedStats['dormitory']->count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700">{{ $detailedStats['dormitory']->latest_date ? $detailedStats['dormitory']->latest_date->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Food Info -->
            <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🥛</span>
                    <h4 class="text-lg font-bold text-gray-800">อาหารเสริม</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-green-600">{{ $detailedStats['food'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">นมคงเหลือ:</span>
                        <span class="font-bold text-green-600">{{ $milkStock }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ขนมปังคงเหลือ:</span>
                        <span class="font-bold text-green-600">{{ $breadStock }}</span>
                    </div>
                </div>
            </div>

            <!-- Hospital Info -->
            <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🏥</span>
                    <h4 class="text-lg font-bold text-gray-800">โรงพยาบาล</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-red-600">{{ $detailedStats['hospital']->count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700">{{ $detailedStats['hospital']->latest_date ? $detailedStats['hospital']->latest_date->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Repair Info -->
            <div class="border-l-4 border-orange-500 bg-orange-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🔧</span>
                    <h4 class="text-lg font-bold text-gray-800">แจ้งซ่อม</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-orange-600">{{ $detailedStats['repair']->count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">รอดำเนินการ:</span>
                        <span class="font-bold text-orange-600">{{ $repairPending }}</span>
                    </div>
                </div>
            </div>

            <!-- Inventory Info -->
            <div class="border-l-4 border-indigo-500 bg-indigo-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📦</span>
                    <h4 class="text-lg font-bold text-gray-800">คลังวัสดุ/ครุภัณฑ์</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-indigo-600">{{ $detailedStats['inventory']->count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">รายการในคลัง:</span>
                        <span class="font-bold text-indigo-600">{{ $inventoryCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Student Count -->
            <div class="border-l-4 border-purple-500 bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">👥</span>
                    <h4 class="text-lg font-bold text-gray-800">นักเรียนทั้งหมด</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนนักเรียน:</span>
                        <span class="font-bold text-purple-600">{{ $stats['student_count'] }} คน</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนหอพัก:</span>
                        <span class="font-bold text-purple-600">{{ $stats['dormitory_list_count'] }} หอ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Records and Inventory -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Records -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📋 บันทึกล่าสุด</h3>
            <div class="space-y-3">
                @forelse($recentRecords as $record)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-800 truncate">
                                {{ $record->dormitory->name ?? $record->type_label }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $record->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <span class="ml-2 text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded whitespace-nowrap">
                            {{ $record->type_label }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">ยังไม่มีข้อมูลบันทึก</p>
                @endforelse
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📦 สถานะคลัง</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="font-medium">🥛 นม</span>
                    <span class="text-xl font-bold text-blue-600">{{ $milkStock }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                    <span class="font-medium">🍞 ขนมปัง</span>
                    <span class="text-xl font-bold text-orange-600">{{ $breadStock }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="font-medium">📦 วัสดุ/ครุภัณฑ์</span>
                    <span class="text-xl font-bold text-purple-600">{{ $inventoryCount }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
