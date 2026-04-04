<?php
include '../config/database.php';

$employees = $conn->query("SELECT id, full_name, position FROM employees WHERE status = 'active'");

$rooms = $conn->query("SELECT id, room_number FROM rooms");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $room_id     = $_POST['room_id'] ?? '';
    $employee_id = $_POST['employee_id'] ?? '';
    $issue       = $_POST['issue'] ?? '';

    // validasi basic
    if (!$room_id || !$employee_id) {
        die("Data tidak lengkap");
    }

    $status = 'pending'; // default

    $stmt = $conn->prepare("INSERT INTO maintenance_tasks 
        (room_id, employee_id, status, issue, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("iiss", $room_id, $employee_id, $status, $issue);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil menambahkan tugas'); window.location='assign-task.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$tasks = $conn->query("
    SELECT 
        mt.*, 
        r.room_number, 
        e.full_name AS employee_name, 
        e.position,
        u.name AS guest_name
    FROM maintenance_tasks mt
    JOIN rooms r ON mt.room_id = r.id
    JOIN employees e ON mt.employee_id = e.id

    LEFT JOIN bookings b 
        ON b.room_id = r.id 
        AND b.status = 'paid'
        AND CURDATE() BETWEEN b.check_in AND b.check_out

    LEFT JOIN users u ON b.user_id = u.id

    ORDER BY mt.created_at DESC
");

ob_start();
?>

<div class="container mt-5">
    <h1 class="text-2xl font-bold mb-6">Assign Maintenance Task</h1>
    <div class="w-full mb-6">
        <form method="POST" action="" class="bg-white shadow-lg rounded-xl p-6">
            <div class="mb-4">
                <label class="block font-semibold mb-2">Pilih Kamar</label>
                <select name="room_id" class="w-full border rounded-lg px-4 py-2" required>
                    <option disabled selected>-- Pilih Kamar --</option>
                    <?php while ($room = $rooms->fetch_assoc()): ?>
                        <option value="<?= $room['id'] ?>">
                            Kamar <?= $room['room_number'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Assign Pegawai</label>
                <select name="employee_id" class="w-full border rounded-lg px-4 py-2" required>
                    <option disabled selected>-- Pilih Pegawai --</option>
                    <?php while ($emp = $employees->fetch_assoc()): ?>
                        <option value="<?= $emp['id'] ?>">
                            <?= $emp['full_name'] ?> (<?= $emp['position'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Catatan</label>
                <textarea name="issue" class="w-full border rounded-lg px-4 py-2" rows="3"></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Assign Task
            </button>
        </form>
    </div>
    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-center">Kamar</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Tamu</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Pegawai</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Catatan</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()): ?>
                <tr class="border-t">
                    <td class="border border-gray-300 px-4 py-2">Room <?= $task['room_number'] ?></td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <?= $task['guest_name'] ? $task['guest_name'] : '-' ?>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <?= $task['employee_name'] ?> (<?= $task['position'] ?>)
                    </td>
                    <td class="border border-gray-300 px-4 py-2"><?= $task['issue'] ?></td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <span class="px-2 py-1 rounded text-sm <?= $task['status'] === 'pending' ? 'bg-yellow-200 text-red-800' : ($task['status'] === 'done' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-yellow-800') ?>">
                            <?= $task['status'] === 'pending' ? 'Pending' : ($task['status'] === 'done' ? 'Done' : 'On Progress') ?>
                        </span>
                    <td class="border border-gray-300 px-4 py-2"><?= $task['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

<?php
$content = ob_get_clean();
$taskactive = 'active';
include 'layouts/app.php';
?>