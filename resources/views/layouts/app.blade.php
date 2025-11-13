<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
        .tab-button {
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        .tab-button.active {
            border-bottom-color: #2563eb;
            color: #2563eb;
        }
        .record-card {
            transition: all 0.2s ease;
        }
        .record-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .slide-in {
            animation: slideIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">{{ env('SYSTEM_TITLE', 'ระบบบริหารกิจการดิจิทัล (DigM-I)') }}</h1>
                    <p class="text-blue-100 mt-1 text-sm md:text-base">{{ env('SCHOOL_NAME', 'โรงเรียนวิทยาศาสตร์จุฬาภรณราชวิทยาลัย กำแพงเพชร') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-100">
                        วันที่: <span id="current-date"></span>
                    </div>
                    <div class="text-sm text-blue-100">
                        เวลา: <span id="current-time"></span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="mt-2 px-4 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors">
                            🚪 ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex space-x-1 overflow-x-auto">
                <a href="{{ route('dashboard') }}" class="tab-button {{ request()->routeIs('dashboard') ? 'active' : '' }} px-4 md:px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    🏠 หน้าหลัก
                </a>
                <a href="{{ route('records') }}" class="tab-button {{ request()->routeIs('records*') ? 'active' : '' }} px-4 md:px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    📝 บันทึกรายงาน
                </a>
                <a href="{{ route('reports') }}" class="tab-button {{ request()->routeIs('reports*') ? 'active' : '' }} px-4 md:px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    📊 รายงาน
                </a>
                <a href="{{ route('management') }}" class="tab-button {{ request()->routeIs('management') ? 'active' : '' }} px-4 md:px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    ⚙️ จัดการข้อมูล
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg fade-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg fade-in">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded-lg fade-in">
                {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">พัฒนาระบบโดย กลุ่มบริหารกิจการนักเรียน {{ env('SCHOOL_NAME') }}</p>
        </div>
    </footer>

    <script>
        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };

            const dateEl = document.getElementById('current-date');
            const timeEl = document.getElementById('current-time');

            if (dateEl) dateEl.textContent = now.toLocaleDateString('th-TH', dateOptions);
            if (timeEl) timeEl.textContent = now.toLocaleTimeString('th-TH', timeOptions);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.fade-in').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
