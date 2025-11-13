<?php
$current_page = 'dashboard';
$page_title = 'หน้าหลัก - ' . SYSTEM_TITLE;

require_once '../includes/header.php';

$db = getDBConnection();

// Get statistics
$stats_query = "SELECT * FROM dashboard_stats";
$stats = $db->query($stats_query)->fetch();

// Get food stock
$food_stock_query = "SELECT * FROM food_stock_summary";
$food_stock = $db->query($food_stock_query)->fetchAll(PDO::FETCH_KEY_PAIR);
$milk_stock = $food_stock['นม'] ?? 0;
$bread_stock = $food_stock['ขนมปัง'] ?? 0;

// Get inventory count
$inventory_query = "SELECT COUNT(*) as count FROM inventory_stock_summary";
$inventory_count = $db->query($inventory_query)->fetch()['count'];

// Get recent records
$recent_query = "
    SELECT r.*, d.name as dormitory_name
    FROM records r
    LEFT JOIN dormitories d ON r.dormitory_id = d.id
    ORDER BY r.created_at DESC
    LIMIT 5
";
$recent_records = $db->query($recent_query)->fetchAll();

// Get record type labels
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

// Get detailed statistics for each type
$detailed_stats = [];

// Dormitory stats
$dorm_stats = $db->query("
    SELECT
        COUNT(*) as count,
        COUNT(DISTINCT dormitory_id) as dorm_count,
        MAX(record_date) as latest_date
    FROM records
    WHERE record_type = 'dormitory'
")->fetch();
$detailed_stats['dormitory'] = $dorm_stats;

// Food stats
$food_stats = $db->query("
    SELECT COUNT(*) as count
    FROM records
    WHERE record_type LIKE 'food%'
")->fetch();
$detailed_stats['food'] = $food_stats;

// Hospital stats
$hospital_stats = $db->query("
    SELECT
        COUNT(*) as count,
        MAX(record_date) as latest_date
    FROM records
    WHERE record_type = 'hospital'
")->fetch();
$detailed_stats['hospital'] = $hospital_stats;

// Other record types stats
$other_types = ['uniform', 'duty', 'repair', 'permission', 'student-leave'];
foreach ($other_types as $type) {
    $type_stats = $db->query("
        SELECT
            COUNT(*) as count,
            MAX(record_date) as latest_date
        FROM records
        WHERE record_type = '$type'
    ")->fetch();
    $detailed_stats[$type] = $type_stats;
}

// Inventory stats
$inv_stats = $db->query("
    SELECT COUNT(*) as count, MAX(record_date) as latest_date
    FROM records
    WHERE record_type LIKE 'inventory%'
")->fetch();
$detailed_stats['inventory'] = $inv_stats;

// Repair pending count
$repair_pending = $db->query("
    SELECT COUNT(*) as count
    FROM repair_records
    WHERE repair_status = 'รอดำเนินการ'
")->fetch()['count'];
?>

<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกเข้าออกหอพัก</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $stats['dormitory_count']; ?></p>
                </div>
                <div class="text-4xl">🏠</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกอาหารเสริม</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $stats['food_count']; ?></p>
                </div>
                <div class="text-4xl">🥛</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกเข้าโรงพยาบาล</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $stats['hospital_count']; ?></p>
                </div>
                <div class="text-4xl">🏥</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">บันทึกทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $stats['total_count']; ?></p>
                </div>
                <div class="text-4xl">📊</div>
            </div>
        </div>
    </div>

    <!-- Information Dashboard -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">📊 สารสนเทศการบันทึกทั้งหมด</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Dormitory Info -->
            <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🏠</span>
                    <h4 class="text-lg font-bold text-gray-800">เข้าออกหอพัก</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-blue-600"><?php echo $detailed_stats['dormitory']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['dormitory']['latest_date'] ? formatThaiDate($detailed_stats['dormitory']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Food Info -->
            <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🥛</span>
                    <h4 class="text-lg font-bold text-gray-800">อาหารเสริม</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-green-600"><?php echo $detailed_stats['food']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">นมคงเหลือ:</span>
                        <span class="font-bold text-green-600"><?php echo $milk_stock; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ขนมปังคงเหลือ:</span>
                        <span class="font-bold text-green-600"><?php echo $bread_stock; ?></span>
                    </div>
                </div>
            </div>

            <!-- Hospital Info -->
            <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🏥</span>
                    <h4 class="text-lg font-bold text-gray-800">โรงพยาบาล</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-red-600"><?php echo $detailed_stats['hospital']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['hospital']['latest_date'] ? formatThaiDate($detailed_stats['hospital']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Uniform Info -->
            <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">👔</span>
                    <h4 class="text-lg font-bold text-gray-800">เครื่องแต่งกาย</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-yellow-600"><?php echo $detailed_stats['uniform']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['uniform']['latest_date'] ? formatThaiDate($detailed_stats['uniform']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Duty Info -->
            <div class="border-l-4 border-purple-500 bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📋</span>
                    <h4 class="text-lg font-bold text-gray-800">ปฏิบัติหน้าที่</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-purple-600"><?php echo $detailed_stats['duty']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['duty']['latest_date'] ? formatThaiDate($detailed_stats['duty']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Repair Info -->
            <div class="border-l-4 border-orange-500 bg-orange-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🔧</span>
                    <h4 class="text-lg font-bold text-gray-800">แจ้งซ่อม</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-orange-600"><?php echo $detailed_stats['repair']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">รอดำเนินการ:</span>
                        <span class="font-bold text-orange-600"><?php echo $repair_pending; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['repair']['latest_date'] ? formatThaiDate($detailed_stats['repair']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Inventory Info -->
            <div class="border-l-4 border-indigo-500 bg-indigo-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📦</span>
                    <h4 class="text-lg font-bold text-gray-800">คลังวัสดุ/ครุภัณฑ์</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-indigo-600"><?php echo $detailed_stats['inventory']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">รายการในคลัง:</span>
                        <span class="font-bold text-indigo-600"><?php echo $inventory_count; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['inventory']['latest_date'] ? formatThaiDate($detailed_stats['inventory']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Permission Info -->
            <div class="border-l-4 border-pink-500 bg-pink-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📄</span>
                    <h4 class="text-lg font-bold text-gray-800">ขออนุญาต</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-pink-600"><?php echo $detailed_stats['permission']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['permission']['latest_date'] ? formatThaiDate($detailed_stats['permission']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Student Leave Info -->
            <div class="border-l-4 border-teal-500 bg-teal-50 p-4 rounded-lg">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📋</span>
                    <h4 class="text-lg font-bold text-gray-800">ขออนุญาตลา (นักเรียน)</h4>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">จำนวนบันทึก:</span>
                        <span class="font-bold text-teal-600"><?php echo $detailed_stats['student-leave']['count']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">บันทึกล่าสุด:</span>
                        <span class="font-medium text-gray-700"><?php echo $detailed_stats['student-leave']['latest_date'] ? formatThaiDate($detailed_stats['student-leave']['latest_date'], 'short') : '-'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Records and Inventory Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Records -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📋 บันทึกล่าสุด</h3>
            <div class="space-y-3">
                <?php if (empty($recent_records)): ?>
                    <p class="text-gray-500 text-center py-8">ยังไม่มีข้อมูลบันทึก</p>
                <?php else: ?>
                    <?php foreach ($recent_records as $record): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-medium text-gray-800">
                                    <?php echo $record['dormitory_name'] ?? getRecordTypeLabel($record['record_type']); ?>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo formatThaiDate($record['created_at'], 'short') . ' ' . date('H:i', strtotime($record['created_at'])); ?>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                <?php echo getRecordTypeLabel($record['record_type']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📦 สถานะคลัง</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="font-medium">🥛 นม</span>
                    <span class="text-xl font-bold text-blue-600"><?php echo $milk_stock; ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                    <span class="font-medium">🍞 ขนมปัง</span>
                    <span class="text-xl font-bold text-orange-600"><?php echo $bread_stock; ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="font-medium">📦 วัสดุ/ครุภัณฑ์</span>
                    <span class="text-xl font-bold text-purple-600"><?php echo $inventory_count; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
