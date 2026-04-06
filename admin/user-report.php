<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$selected_month = $_GET['month'] ?? date('n');
$selected_year  = $_GET['year'] ?? date('Y');

$employees = $conn->query("SELECT id, nickname FROM users WHERE role = 'pegawai'");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_report'])) {
    $report_id = $_POST['report_id'];
    $worker_id = $_POST['worker_id'];

    $stmt = $conn->prepare("UPDATE maintenance_requests SET status = 'on_progress', employee_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $worker_id, $report_id);

    if ($stmt->execute()) {
        echo "<script>alert('Laporan diteruskan ke pegawai!'); window.location='user-report.php?month=$selected_month&year=$selected_year';</script>";
        exit();
    }
}

$reports = $conn->query("
    SELECT mr.*, u.nickname as reporter_name
    FROM maintenance_requests mr
    JOIN users u ON mr.user_id = u.id
    WHERE MONTH(mr.created_at) = '$selected_month' 
    AND YEAR(mr.created_at) = '$selected_year'
    ORDER BY mr.created_at DESC
");

ob_start();
?>

<div class="container mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 text-left">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Keluhan User</h1>

        <form method="GET" class="flex items-center gap-3 bg-white p-2 rounded-lg shadow border border-gray-200">
            <div class="flex items-center gap-2 px-2 border-r border-gray-200">
                <i class="fas fa-calendar-alt text-blue-500"></i>
                <select name="month" onchange="this.form.submit()" class="text-sm font-semibold text-gray-700 bg-transparent outline-none cursor-pointer">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <input type="number" name="year" value="<?= $selected_year ?>" onchange="this.form.submit()"
                class="w-20 text-sm font-semibold text-gray-700 bg-transparent text-center outline-none">
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-100 text-left">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Laporan Masalah</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Lokasi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Pelapor</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Masalah</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase text-center">Foto</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase text-center">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if ($reports->num_rows > 0): ?>
                        <?php while ($row = $reports->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900"><?= $row['location'] ?></td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium uppercase"><?= $row['reporter_name'] ?></td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800"><?= $row['issue_name'] ?></p>
                                    <p class="text-xs text-gray-500 truncate max-w-[200px]"><?= $row['description'] ?></p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($row['photo']): ?>
                                        <button onclick="openPhotoModal('/kos-native/assets/img/reports/<?= $row['photo'] ?>')"
                                            class="text-blue-500 hover:text-blue-700 transition-transform active:scale-90">
                                            <i class="fas <?= $row['status'] === 'done' ? 'fa-check-circle text-green-500' : 'fa-image' ?> text-lg"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-300 italic text-xs">No Photo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php
                                    $status = $row['status'];
                                    $badge_class = "bg-amber-100 text-amber-800";
                                    if ($status === 'done') $badge_class = "bg-green-100 text-green-800";
                                    if ($status === 'on_progress') $badge_class = "bg-blue-100 text-blue-800";
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase <?= $badge_class ?>">
                                        <?= str_replace('_', ' ', $status) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($status === 'pending'): ?>
                                        <button onclick="openAssignModal(<?= $row['id'] ?>)" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase px-4 py-2 rounded-lg transition-all shadow-md">
                                            Assign
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-[10px] font-bold uppercase italic">Diteruskan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                Tidak ada laporan user untuk periode ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="photoModal" class="fixed inset-0 bg-black/80 hidden justify-center items-center z-[60] backdrop-blur-sm p-4" onclick="closePhotoModal()">
    <div class="relative max-w-4xl w-full flex justify-center" onclick="event.stopPropagation()">
        <button onclick="closePhotoModal()" class="absolute -top-12 right-0 text-white text-3xl hover:text-gray-300 transition-all">&times;</button>
        <img id="modalImg" src="" class="rounded-2xl shadow-2xl max-h-[80vh] w-auto object-contain border-4 border-white/10">
    </div>
</div>

<div id="assignModal" class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50 backdrop-blur-sm p-4">
    <div class="bg-white p-8 rounded-2xl w-full max-w-sm shadow-2xl">
        <h2 class="text-xl font-bold mb-4 text-gray-900 text-left">Teruskan Laporan</h2>
        <form method="POST">
            <input type="hidden" name="report_id" id="report_id">
            <div class="mb-6 text-left">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pegawai Pelaksana</label>
                <select name="worker_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    <?php if ($employees->num_rows > 0): $employees->data_seek(0); ?>
                        <?php while ($emp = $employees->fetch_assoc()): ?>
                            <option value="<?= $emp['id'] ?>"><?= $emp['nickname'] ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeAssignModal()" class="flex-1 px-4 py-3 bg-gray-100 rounded-xl font-bold text-gray-600 text-sm">Batal</button>
                <button type="submit" name="assign_report" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200">Kirim Tugas</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPhotoModal(src) {
        const modal = document.getElementById('photoModal');
        const img = document.getElementById('modalImg');
        img.src = src;
        modal.classList.replace('hidden', 'flex');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.replace('flex', 'hidden');
    }

    function openAssignModal(id) {
        document.getElementById('report_id').value = id;
        document.getElementById('assignModal').classList.replace('hidden', 'flex');
    }

    function closeAssignModal() {
        document.getElementById('assignModal').classList.replace('flex', 'hidden');
    }
</script>

<?php
$content = ob_get_clean();
$reportactive = 'active';
include 'layouts/app.php';
?>