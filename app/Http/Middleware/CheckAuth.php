<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::get('logged_in')) {
            return redirect()->route('login');
        }

        // Check session timeout (30 minutes)
        $loginTime = Session::get('login_time');
        if ($loginTime && now()->diffInMinutes($loginTime) > 30) {
            Session::flush();
            return redirect()->route('login')->with('error', 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบอีกครั้ง');
        }

        // Update last activity
        Session::put('login_time', now());

        return $next($request);
    }
}
