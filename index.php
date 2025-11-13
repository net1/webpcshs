<?php
require_once 'config/config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: pages/dashboard.php');
    exit;
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    // Simple password check (admin123 or pcshskp123)
    if ($password === 'admin123' || $password === 'pcshskp123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();
        $_SESSION['login_time'] = time();
        header('Location: pages/dashboard.php');
        exit;
    } else {
        $error = 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - <?php echo SYSTEM_TITLE; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
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
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full slide-in">
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">🔐</div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">เข้าสู่ระบบ</h2>
            <p class="text-gray-600"><?php echo SYSTEM_TITLE; ?></p>
            <p class="text-sm text-gray-500 mt-1"><?php echo SCHOOL_NAME; ?></p>
        </div>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">รหัสผ่าน</label>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="กรอกรหัสผ่าน"
                    required
                    autofocus
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors"
            >
                เข้าสู่ระบบ
            </button>
        </form>

        <?php if ($error): ?>
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm text-center">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>รหัสผ่าน: admin123 หรือ pcshskp123</p>
        </div>
    </div>
</body>
</html>
