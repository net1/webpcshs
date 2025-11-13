<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::get('logged_in')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $password = $request->input('password');

        // Simple password check
        if ($password === 'admin123' || $password === 'pcshskp123') {
            Session::put('logged_in', true);
            Session::put('login_time', now());

            return redirect()->route('dashboard')->with('success', 'เข้าสู่ระบบสำเร็จ');
        }

        return back()->withErrors([
            'password' => 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง'
        ]);
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'ออกจากระบบสำเร็จ');
    }
}
