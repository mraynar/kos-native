<?php
include '../config/database.php';

$employees = $conn->query("SELECT id, full_name, employee_code FROM employees WHERE status='active'");

$services = $conn->query("SELECT id, service_name FROM additional_services");

$tasks = $conn->query("
    SELECT 
        bs.id,
        bs.booking_id,
        bs.quantity,
        bs.service_status,
        bs.employee_id,

        b.room_id,
        r.room_number,

        u.name AS guest_name,

        s.service_name,

        e.full_name,
        e.employee_code

    FROM booking_service bs
    JOIN bookings b ON bs.booking_id = b.id
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.user_id = u.id
    JOIN additional_services s ON bs.additional_service_id = s.id

    LEFT JOIN employees e ON bs.employee_id = e.id

    ORDER BY bs.created_at DESC
");

$unassigned = $conn->query("
    SELECT 
        bs.id,
        s.service_name,
        r.room_number,
        u.name AS guest_name
    FROM booking_service bs
    JOIN bookings b ON bs.booking_id = b.id
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.user_id = u.id
    JOIN additional_services s ON bs.additional_service_id = s.id
    WHERE bs.employee_id IS NULL
");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $booking_service_id = $_POST['booking_service_id'] ?? null;
    $employee_id        = $_POST['employee_id'] ?? null;

    if (!$booking_service_id || !$employee_id) {
        die("<script>alert('Data tidak lengkap'); window.location='assign-task.php';</script>");
    }

    $stmt = $conn->prepare("
        UPDATE booking_service 
        SET employee_id = ?, service_status = 'on_progress'
        WHERE id = ?
    ");

    $stmt->bind_param("ii", $employee_id, $booking_service_id);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil assign'); window.location='assign-task.php';</script>";
    } else {
        echo "Gagal assign: " . $stmt->error;
    }
}

ob_start();
?>

<div class="container">
    <h1 class="text-2xl font-bold mb-6">Assign Maintenance Task</h1>
    <div class="w-full mb-6">
        <form method="POST" class="bg-white shadow-lg rounded-xl p-6 mb-6">

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-semibold mb-2">Bulan</label>
                    <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>"><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Tahun</label>
                    <input type="number" name="year" value="<?= date('Y') ?>"
                        class="w-full border rounded-lg px-4 py-2" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-2">Pilih Pegawai</label>
                    <select name="employee_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option disabled selected>-- Pilih Pegawai --</option>
                        <?php while ($emp = $employees->fetch_assoc()): ?>
                            <option value="<?= $emp['id'] ?>">
                                <?= $emp['employee_code'] ?> - <?= $emp['full_name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-2">Service</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name=" booking_service_id" required>
                        <option disabled selected>-- Pilih Task --</option>
                        <?php while ($row = $unassigned->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['service_name'] ?> - Room <?= $row['room_number'] ?> (<?= $row['guest_name'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg">
                Assign
            </button>

        </form>
    </div>
    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-center">Kamar</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Tamu</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Servis</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Pegawai</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()): ?>
                <tr class="hover:bg-gray-100 border-b border-gray-300">
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        Room <?= $task['room_number'] ?>
                    </td>

                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <?= $task['guest_name'] ?>
                    </td>

                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <?= $task['service_name'] ?>
                    </td>

                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <?= $task['employee_id']
                            ? $task['employee_code'] . ' - ' . $task['full_name']
                            : '-' ?>
                    </td>

                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <span class="px-2 py-1 rounded text-sm <?= $task['service_status'] === 'completed' ? 'bg-green-200 text-green-800' : ($task['service_status'] === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') ?>"><?= ucfirst(str_replace('_', ' ', $task['service_status'])) ?></span>
                    </td>
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