<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index()
    {
        return view('records.index');
    }

    // TODO: Add methods for creating different record types
    // - storeDormitoryRecord
    // - storeFoodRecord
    // - storeHospitalRecord
    // etc.
}
