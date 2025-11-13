<?php
if (!defined('BASE_PATH')) {
    require_once dirname(__DIR__) . '/config/config.php';
}
requireLogin();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? SYSTEM_TITLE; ?></title>
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
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .toast.success { background-color: #10b981; }
        .toast.error { background-color: #ef4444; }
        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #2563eb;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold"><?php echo SYSTEM_TITLE; ?></h1>
                    <p class="text-blue-100 mt-1"><?php echo SCHOOL_NAME; ?></p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-100">
                        วันที่: <span id="current-date"></span>
                    </div>
                    <div class="text-sm text-blue-100">
                        เวลา: <span id="current-time"></span>
                    </div>
                    <a href="../logout.php" class="mt-2 inline-block px-4 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors">
                        🚪 ออกจากระบบ
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="bg-white shadow-md sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex space-x-1 overflow-x-auto">
                <a href="dashboard.php" class="tab-button <?php echo ($current_page ?? '') === 'dashboard' ? 'active' : ''; ?> px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    🏠 หน้าหลัก
                </a>
                <a href="records.php" class="tab-button <?php echo ($current_page ?? '') === 'records' ? 'active' : ''; ?> px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    📝 บันทึกรายงาน
                </a>
                <a href="reports.php" class="tab-button <?php echo ($current_page ?? '') === 'reports' ? 'active' : ''; ?> px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    📊 รายงาน
                </a>
                <a href="management.php" class="tab-button <?php echo ($current_page ?? '') === 'management' ? 'active' : ''; ?> px-6 py-4 text-gray-700 font-medium whitespace-nowrap hover:text-blue-600">
                    ⚙️ จัดการข้อมูล
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
