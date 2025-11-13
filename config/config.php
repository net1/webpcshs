<?php
// Application Configuration
session_start();

// Timezone
date_default_timezone_set('Asia/Bangkok');

// Base paths
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/');

// Include database config
require_once BASE_PATH . '/config/database.php';

// System settings (can be overridden from database)
define('SYSTEM_TITLE', 'ระบบบริหารกิจการดิจิทัล (DigM-I)');
define('SCHOOL_NAME', 'โรงเรียนวิทยาศาสตร์จุฬาภรณราชวิทยาลัย กำแพงเพชร');
define('APPROVAL_CODE', '2812');
define('MAX_RECORDS', 999);

// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 1800);

// Check if user is logged in
function isLoggedIn() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        return false;
    }

    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        return false;
    }

    $_SESSION['last_activity'] = time();
    return true;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /index.php');
        exit;
    }
}

// Sanitize input
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Format Thai date
function formatThaiDate($date, $format = 'long') {
    $timestamp = strtotime($date);
    if ($format === 'long') {
        $thai_months = [
            1 => 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];
        $day = date('j', $timestamp);
        $month = $thai_months[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp) + 543;
        return "$day $month $year";
    } else {
        return date('d/m/Y', $timestamp);
    }
}

// Get system setting
function getSystemSetting($key, $default = null) {
    $db = getDBConnection();
    $stmt = $db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

// JSON response helper
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
