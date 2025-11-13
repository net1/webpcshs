@extends('layouts.app')

@section('title', 'จัดการข้อมูล - ' . config('app.name'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Student Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">👥 จัดการข้อมูลนักเรียน</h3>

        <form method="POST" action="{{ route('students.store') }}" class="space-y-4 mb-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="firstname" placeholder="ชื่อ" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                <input type="text" name="lastname" placeholder="นามสกุล" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <input type="text" name="student_id" placeholder="รหัสนักเรียน" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <select name="grade" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- เลือกระดับชั้น --</option>
                    <option value="ม.1">ม.1</option>
                    <option value="ม.2">ม.2</option>
                    <option value="ม.3">ม.3</option>
                    <option value="ม.4">ม.4</option>
                    <option value="ม.5">ม.5</option>
                    <option value="ม.6">ม.6</option>
                </select>
                <select name="dormitory_id" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- เลือกหอพัก --</option>
                    @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}">{{ $dorm->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">เพิ่มนักเรียน</button>
        </form>
    </div>

    <!-- Dormitory Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🏠 จัดการหอพัก</h3>

        <form method="POST" action="{{ route('dormitories.store') }}" class="space-y-4 mb-6">
            @csrf
            <input type="text" name="name" placeholder="ชื่อหอพัก (เช่น หอ 5 (ชาย))" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">เพิ่มหอพัก</button>
        </form>

        <div class="border-t pt-4">
            <h4 class="font-bold text-gray-800 mb-3">รายการหอพัก</h4>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @forelse($dormitories as $dorm)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <span>{{ $dorm->name }}</span>
                        <form method="POST" action="{{ route('dormitories.destroy', $dorm) }}" class="inline" onsubmit="return confirm('ยืนยันการลบหอพัก?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">ลบ</button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">ยังไม่มีหอพัก</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📋 รายชื่อนักเรียนทั้งหมด ({{ $students->count() }} คน)</h3>
        <div class="mb-4">
            <input type="text" id="student-search" placeholder="ค้นหานักเรียน..." class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">รหัส</th>
                        <th class="px-4 py-2 text-left">ชื่อ-นามสกุล</th>
                        <th class="px-4 py-2 text-left">ชั้น</th>
                        <th class="px-4 py-2 text-left">หอพัก</th>
                        <th class="px-4 py-2 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="student-tbody">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 student-row">
                            <td class="px-4 py-2 border-t">{{ $student->student_id }}</td>
                            <td class="px-4 py-2 border-t">{{ $student->full_name }}</td>
                            <td class="px-4 py-2 border-t">{{ $student->grade }}</td>
                            <td class="px-4 py-2 border-t">{{ $student->dormitory->name }}</td>
                            <td class="px-4 py-2 border-t text-center">
                                <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline" onsubmit="return confirm('ยืนยันการลบ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">ยังไม่มีข้อมูลนักเรียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('student-search')?.addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.student-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
