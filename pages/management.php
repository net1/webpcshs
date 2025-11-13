<?php
$current_page = 'management';
$page_title = 'จัดการข้อมูล - ' . SYSTEM_TITLE;

require_once '../includes/header.php';

$db = getDBConnection();

// Get all dormitories
$dormitories = $db->query("SELECT * FROM dormitories ORDER BY name")->fetchAll();

// Get all students with dormitory info
$students = $db->query("
    SELECT s.*, d.name as dormitory_name
    FROM students s
    LEFT JOIN dormitories d ON s.dormitory_id = d.id
    ORDER BY s.grade, s.lastname, s.firstname
")->fetchAll();

// Handle form submissions
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Add Dormitory
        if (isset($_POST['action']) && $_POST['action'] === 'add_dormitory') {
            $name = sanitize($_POST['dorm_name']);

            $stmt = $db->prepare("INSERT INTO dormitories (name) VALUES (?)");
            $stmt->execute([$name]);

            $success_message = 'เพิ่มหอพักสำเร็จ';
            header('Location: management.php?success=dorm_added');
            exit;
        }

        // Add Student
        if (isset($_POST['action']) && $_POST['action'] === 'add_student') {
            $firstname = sanitize($_POST['firstname']);
            $lastname = sanitize($_POST['lastname']);
            $student_id = sanitize($_POST['student_id']);
            $grade = sanitize($_POST['grade']);
            $dormitory_id = (int)$_POST['dormitory_id'];

            $stmt = $db->prepare("
                INSERT INTO students (firstname, lastname, student_id, grade, dormitory_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$firstname, $lastname, $student_id, $grade, $dormitory_id]);

            $success_message = 'เพิ่มนักเรียนสำเร็จ';
            header('Location: management.php?success=student_added');
            exit;
        }

        // Delete Dormitory
        if (isset($_POST['action']) && $_POST['action'] === 'delete_dormitory') {
            $id = (int)$_POST['dorm_id'];

            // Check if dormitory has students
            $check = $db->prepare("SELECT COUNT(*) FROM students WHERE dormitory_id = ?");
            $check->execute([$id]);
            if ($check->fetchColumn() > 0) {
                $error_message = 'ไม่สามารถลบหอพักที่มีนักเรียนอยู่';
            } else {
                $stmt = $db->prepare("DELETE FROM dormitories WHERE id = ?");
                $stmt->execute([$id]);
                $success_message = 'ลบหอพักสำเร็จ';
                header('Location: management.php?success=dorm_deleted');
                exit;
            }
        }

        // Delete Student
        if (isset($_POST['action']) && $_POST['action'] === 'delete_student') {
            $id = (int)$_POST['student_id'];

            $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute([$id]);

            $success_message = 'ลบนักเรียนสำเร็จ';
            header('Location: management.php?success=student_deleted');
            exit;
        }
    } catch (PDOException $e) {
        $error_message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    }
}

// Success messages from redirect
if (isset($_GET['success'])) {
    $messages = [
        'dorm_added' => 'เพิ่มหอพักสำเร็จ',
        'student_added' => 'เพิ่มนักเรียนสำเร็จ',
        'dorm_deleted' => 'ลบหอพักสำเร็จ',
        'student_deleted' => 'ลบนักเรียนสำเร็จ',
        'excel_uploaded' => 'อัพโหลดไฟล์ Excel สำเร็จ'
    ];
    $success_message = $messages[$_GET['success']] ?? '';
}
?>

<?php if ($success_message): ?>
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Student Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">👥 จัดการข้อมูลนักเรียน</h3>

        <form method="POST" action="" class="space-y-4 mb-6">
            <input type="hidden" name="action" value="add_student">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อ</label>
                <input type="text" name="firstname" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">นามสกุล</label>
                <input type="text" name="lastname" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">รหัสนักเรียน</label>
                <input type="text" name="student_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ระดับชั้น</label>
                <select name="grade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- เลือกระดับชั้น --</option>
                    <option value="ม.1">ม.1</option>
                    <option value="ม.2">ม.2</option>
                    <option value="ม.3">ม.3</option>
                    <option value="ม.4">ม.4</option>
                    <option value="ม.5">ม.5</option>
                    <option value="ม.6">ม.6</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">หอพัก</label>
                <select name="dormitory_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- เลือกหอพัก --</option>
                    <?php foreach ($dormitories as $dorm): ?>
                        <option value="<?php echo $dorm['id']; ?>"><?php echo $dorm['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700">
                เพิ่มนักเรียน
            </button>
        </form>

        <div class="border-t pt-4">
            <h4 class="font-bold text-gray-800 mb-3">📤 อัพโหลดไฟล์ Excel</h4>
            <p class="text-sm text-gray-600 mb-3">รูปแบบไฟล์: ชื่อ | นามสกุล | รหัสนักเรียน | ระดับชั้น | ชื่อหอพัก</p>
            <form action="../api/upload_excel.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="excel_file" accept=".xlsx,.xls" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 mb-2" required>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg font-medium hover:bg-green-700">
                    อัพโหลดไฟล์
                </button>
            </form>
        </div>
    </div>

    <!-- Dormitory Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🏠 จัดการหอพัก</h3>

        <form method="POST" action="" class="space-y-4 mb-6">
            <input type="hidden" name="action" value="add_dormitory">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อหอพัก</label>
                <input type="text" name="dorm_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="เช่น หอ 5 (ชาย)" required>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-medium hover:bg-indigo-700">
                เพิ่มหอพัก
            </button>
        </form>

        <div class="border-t pt-4">
            <h4 class="font-bold text-gray-800 mb-3">รายการหอพัก</h4>
            <div class="space-y-2">
                <?php if (empty($dormitories)): ?>
                    <p class="text-gray-500 text-sm">ยังไม่มีหอพัก</p>
                <?php else: ?>
                    <?php foreach ($dormitories as $dorm): ?>
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span><?php echo $dorm['name']; ?></span>
                            <form method="POST" action="" class="inline" onsubmit="return confirm('ยืนยันการลบหอพัก?')">
                                <input type="hidden" name="action" value="delete_dormitory">
                                <input type="hidden" name="dorm_id" value="<?php echo $dorm['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">ลบ</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📋 รายชื่อนักเรียนทั้งหมด (<?php echo count($students); ?> คน)</h3>

        <div class="mb-4">
            <input type="text" id="student-search" placeholder="ค้นหานักเรียน..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="student-table">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">รหัสนักเรียน</th>
                        <th class="px-4 py-2 text-left">ชื่อ-นามสกุล</th>
                        <th class="px-4 py-2 text-left">ระดับชั้น</th>
                        <th class="px-4 py-2 text-left">หอพัก</th>
                        <th class="px-4 py-2 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">ยังไม่มีข้อมูลนักเรียน</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr class="hover:bg-gray-50 student-row">
                                <td class="px-4 py-2 border-t"><?php echo $student['student_id']; ?></td>
                                <td class="px-4 py-2 border-t"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                <td class="px-4 py-2 border-t"><?php echo $student['grade']; ?></td>
                                <td class="px-4 py-2 border-t"><?php echo $student['dormitory_name']; ?></td>
                                <td class="px-4 py-2 border-t text-center">
                                    <form method="POST" action="" class="inline" onsubmit="return confirm('ยืนยันการลบนักเรียน?')">
                                        <input type="hidden" name="action" value="delete_student">
                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                        <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Student search functionality
document.getElementById('student-search')?.addEventListener('keyup', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
