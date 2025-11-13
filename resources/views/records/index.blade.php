@extends('layouts.app')

@section('title', 'บันทึกรายงาน - ' . config('app.name'))

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📝 เลือกประเภทการบันทึก</h2>

    <div class="max-w-md">
        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกประเภทการบันทึก</label>
        <select class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 text-lg">
            <option value="">-- เลือกประเภทการบันทึก --</option>
            <option value="dormitory">🏠 เข้าออกหอพัก</option>
            <option value="food">🥛 อาหารเสริม</option>
            <option value="hospital">🏥 เข้าออกโรงพยาบาล</option>
            <option value="uniform">👔 ตรวจเครื่องแต่งกาย</option>
            <option value="duty">📋 ปฏิบัติหน้าที่ประจำวัน</option>
            <option value="repair">🔧 แจ้งซ่อม</option>
            <option value="inventory">📦 คลังวัสดุ/ครุภัณฑ์</option>
            <option value="permission">📄 ขออนุญาต/อนุมัติ</option>
            <option value="student-leave">📋 ขออนุญาตลา (นักเรียน)</option>
        </select>
    </div>
</div>

<div class="bg-blue-50 border-l-4 border-blue-500 p-4">
    <p class="text-blue-800"><strong>หมายเหตุ:</strong> ระบบบันทึกรายงานจะเปิดให้ใช้งานในเร็วๆ นี้</p>
    <p class="text-blue-700 text-sm mt-2">ขณะนี้คุณสามารถ:</p>
    <ul class="list-disc ml-5 mt-2 text-blue-700 text-sm">
        <li>ดูสถิติการบันทึกที่หน้าหลัก</li>
        <li>จัดการข้อมูลนักเรียนและหอพักที่เมนูจัดการข้อมูล</li>
        <li>ดูรายงานที่หน้ารายงาน</li>
    </ul>
</div>
@endsection
