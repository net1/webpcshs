<?php
require_once '../config/config.php';
requireLogin();

// Note: This is a placeholder for Excel upload functionality
// You'll need to install PHPSpreadsheet via Composer:
// composer require phpoffice/phpspreadsheet

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์';
        header('Location: ../pages/management.php?error=upload_failed');
        exit;
    }

    // Check file type
    $allowed_types = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types)) {
        $response['message'] = 'กรุณาอัพโหลดไฟล์ Excel (.xlsx หรือ .xls)';
        header('Location: ../pages/management.php?error=invalid_file_type');
        exit;
    }

    // TODO: Implement PHPSpreadsheet to read Excel file
    // For now, show a placeholder message

    header('Location: ../pages/management.php?success=excel_uploaded');
    exit;
}

header('Location: ../pages/management.php?error=no_file');
exit;
