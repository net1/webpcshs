<?php
$current_page = 'reports';
$page_title = 'รายงาน - ' . SYSTEM_TITLE;

require_once '../includes/header.php';

$db = getDBConnection();

// Get filter parameters
$filter_type = $_GET['type'] ?? '';
$filter_status = $_GET['status'] ?? '';

// Build query
$query = "
    SELECT r.*, d.name as dormitory_name
    FROM records r
    LEFT JOIN dormitories d ON r.dormitory_id = d.id
    WHERE 1=1
";

$params = [];

if ($filter_type) {
    $query .= " AND r.record_type = ?";
    $params[] = $filter_type;
}

if ($filter_status) {
    $query .= " AND r.report_status = ?";
    $params[] = $filter_status;
}

$query .= " ORDER BY r.created_at DESC LIMIT 100";

$stmt = $db->prepare($query);
$stmt->execute($params);
$reports = $stmt->fetchAll();

function getRecordTypeLabel($type) {
    $labels = [
        'dormitory' => 'เข้าออกหอพัก',
        'food-stock' => 'รับอาหารเสริม',
        'food-distribute' => 'จ่ายอาหารเสริม',
        'hospital' => 'โรงพยาบาล',
        'uniform' => 'เครื่องแต่งกาย',
        'duty' => 'ปฏิบัติหน้าที่',
        'repair' => 'แจ้งซ่อม',
        'inventory-receive' => 'รับวัสดุ',
        'inventory-issue' => 'จ่ายวัสดุ',
        'permission' => 'ขออนุญาต',
        'student-leave' => 'ขออนุญาตลา'
    ];
    return $labels[$type] ?? 'อื่นๆ';
}

function getStatusColor($status) {
    return $status === 'รับรองรายงาน' ? 'green' : ($status === 'ไม่รับรองรายงาน' ? 'red' : 'yellow');
}
?>

<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 รายงานทั้งหมด</h2>

    <!-- Filters -->
    <form method="GET" class="mb-4 flex gap-4 flex-wrap">
        <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกประเภท</option>
            <option value="dormitory" <?php echo $filter_type === 'dormitory' ? 'selected' : ''; ?>>เข้าออกหอพัก</option>
            <option value="food-stock" <?php echo $filter_type === 'food-stock' ? 'selected' : ''; ?>>อาหารเสริม (รับ)</option>
            <option value="food-distribute" <?php echo $filter_type === 'food-distribute' ? 'selected' : ''; ?>>อาหารเสริม (จ่าย)</option>
            <option value="hospital" <?php echo $filter_type === 'hospital' ? 'selected' : ''; ?>>โรงพยาบาล</option>
            <option value="uniform" <?php echo $filter_type === 'uniform' ? 'selected' : ''; ?>>เครื่องแต่งกาย</option>
            <option value="duty" <?php echo $filter_type === 'duty' ? 'selected' : ''; ?>>ปฏิบัติหน้าที่</option>
            <option value="repair" <?php echo $filter_type === 'repair' ? 'selected' : ''; ?>>แจ้งซ่อม</option>
            <option value="inventory-receive" <?php echo $filter_type === 'inventory-receive' ? 'selected' : ''; ?>>คลังวัสดุ (รับ)</option>
            <option value="inventory-issue" <?php echo $filter_type === 'inventory-issue' ? 'selected' : ''; ?>>คลังวัสดุ (จ่าย)</option>
            <option value="permission" <?php echo $filter_type === 'permission' ? 'selected' : ''; ?>>ขออนุญาต</option>
            <option value="student-leave" <?php echo $filter_type === 'student-leave' ? 'selected' : ''; ?>>ขออนุญาตลา (นักเรียน)</option>
        </select>

        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">ทุกสถานะ</option>
            <option value="รอรับรองรายงาน" <?php echo $filter_status === 'รอรับรองรายงาน' ? 'selected' : ''; ?>>รอรับรองรายงาน</option>
            <option value="รับรองรายงาน" <?php echo $filter_status === 'รับรองรายงาน' ? 'selected' : ''; ?>>รับรองรายงาน</option>
            <option value="ไม่รับรองรายงาน" <?php echo $filter_status === 'ไม่รับรองรายงาน' ? 'selected' : ''; ?>>ไม่รับรองรายงาน</option>
        </select>

        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            🔍 กรอง
        </button>
        <a href="reports.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            ล้างตัวกรอง
        </a>
    </form>

    <!-- Reports List -->
    <div class="space-y-3">
        <?php if (empty($reports)): ?>
            <p class="text-gray-500 text-center py-8">ไม่พบรายงาน</p>
        <?php else: ?>
            <?php foreach ($reports as $report): ?>
                <?php $color = getStatusColor($report['report_status']); ?>
                <div class="border-l-4 border-<?php echo $color; ?>-500 bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                        <div class="flex-1">
                            <div class="font-bold text-gray-800">
                                <?php echo getRecordTypeLabel($report['record_type']); ?> -
                                <?php echo $report['dormitory_name'] ?? 'ทั่วไป'; ?>
                            </div>
                            <div class="text-sm text-gray-500">
                                <?php echo formatThaiDate($report['record_date']) . ' | บันทึกโดย: ' . $report['recorder']; ?>
                            </div>
                            <div class="mt-2">
                                <span class="inline-block px-3 py-1 text-sm rounded-full bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-700">
                                    <?php echo $report['report_status']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <a href="view_report.php?id=<?php echo $report['id']; ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm whitespace-nowrap">
                                👁️ ดูรายงาน
                            </a>
                        </div>
                    </div>
                    <?php if ($report['details']): ?>
                        <div class="text-sm text-gray-600 mt-2">
                            <?php echo nl2br(substr($report['details'], 0, 150)) . (strlen($report['details']) > 150 ? '...' : ''); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
