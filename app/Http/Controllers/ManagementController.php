<?php

namespace App\Http\Controllers;

use App\Models\Dormitory;
use App\Models\Student;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function index()
    {
        $dormitories = Dormitory::orderBy('name')->get();
        $students = Student::with('dormitory')
            ->orderBy('grade')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();

        return view('management.index', compact('dormitories', 'students'));
    }

    public function storeDormitory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:dormitories|max:100'
        ]);

        Dormitory::create(['name' => $request->name]);

        return redirect()->route('management')->with('success', 'เพิ่มหอพักสำเร็จ');
    }

    public function destroyDormitory(Dormitory $dormitory)
    {
        if ($dormitory->students()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบหอพักที่มีนักเรียนอยู่');
        }

        $dormitory->delete();

        return redirect()->route('management')->with('success', 'ลบหอพักสำเร็จ');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'student_id' => 'required|unique:students|max:20',
            'grade' => 'required|max:10',
            'dormitory_id' => 'required|exists:dormitories,id'
        ]);

        Student::create($request->all());

        return redirect()->route('management')->with('success', 'เพิ่มนักเรียนสำเร็จ');
    }

    public function destroyStudent(Student $student)
    {
        $student->delete();

        return redirect()->route('management')->with('success', 'ลบนักเรียนสำเร็จ');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        // TODO: Implement Excel upload using PhpSpreadsheet

        return redirect()->route('management')->with('info', 'ฟีเจอร์อัพโหลด Excel จะเปิดใช้งานในเร็วๆ นี้');
    }
}
