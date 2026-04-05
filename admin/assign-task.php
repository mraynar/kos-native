<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$selected_month = $_GET['month'] ?? date('n');
$selected_year  = $_GET['year'] ?? date('Y');
$selected_category = $_GET['category'] ?? 'Semua';
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_assign'])) {
    $employee_id = $_POST['employee_id'] ?? null;
    $selected_services = $_POST['selected_services'] ?? [];

    if ($employee_id && !empty($selected_services)) {
        $ids = implode(',', array_map('intval', $selected_services));
        $query = "UPDATE booking_service SET employee_id = '$employee_id', service_status = 'on_progress' WHERE id IN ($ids)";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Berhasil menugaskan " . count($selected_services) . " layanan!'); window.location='assign-task.php?month=$selected_month&year=$selected_year&category=$selected_category';</script>";
            exit();
        }
    }
}

$employees = $conn->query("SELECT id, nickname, name FROM users WHERE role = 'pegawai'");

$whereClause = "WHERE MONTH(bs.created_at) = '$selected_month' AND YEAR(bs.created_at) = '$selected_year'";
if ($selected_category !== 'Semua') {
    $whereClause .= " AND s.service_name LIKE '%$selected_category%'";
}

$tasks = $conn->query("
    SELECT 
        bs.*, b.room_id, r.room_number, u.name AS guest_name, s.service_name,
        emp.nickname AS employee_name,
        (SELECT COUNT(*) FROM service_reports WHERE booking_service_id = bs.id AND report_date = '$today') as daily_done
    FROM booking_service bs
    JOIN bookings b ON bs.booking_id = b.id
    JOIN rooms r ON b.room_id = r.id
    JOIN users u ON b.user_id = u.id
    JOIN additional_services s ON bs.additional_service_id = s.id
    LEFT JOIN users emp ON bs.employee_id = emp.id AND emp.role = 'pegawai'
    $whereClause
    ORDER BY (bs.employee_id IS NULL OR bs.employee_id = 0) DESC, bs.created_at DESC
");

ob_start();
?>

<div class="container mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 text-left">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penugasan Layanan</h1>
            <p class="text-sm text-gray-500 font-medium">Monitoring status pengerjaan harian staf operasional.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex gap-1 bg-white p-1 rounded-xl shadow-sm border border-gray-100">
                <?php
                $categories = ['Semua', 'Catering', 'Laundry', 'Cleaning'];
                foreach ($categories as $cat):
                ?>
                    <a href="?month=<?= $selected_month ?>&year=<?= $selected_year ?>&category=<?= $cat ?>"
                        class="<?= $selected_category == $cat ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50' ?> px-4 py-2 rounded-lg text-[10px] font-black uppercase transition">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <form method="GET" class="flex items-center gap-3 bg-white p-2 rounded-lg shadow border border-gray-200">
                <input type="hidden" name="category" value="<?= $selected_category ?>">
                <div class="flex items-center gap-2 px-2 border-r border-gray-200">
                    <i class="fas fa-calendar-alt text-blue-500 text-xs"></i>
                    <select name="month" onchange="this.form.submit()" class="text-[11px] font-bold text-gray-700 bg-transparent outline-none cursor-pointer uppercase">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $selected_month == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <input type="number" name="year" value="<?= $selected_year ?>" onchange="this.form.submit()"
                    class="w-16 text-[11px] font-bold text-gray-700 bg-transparent text-center outline-none font-bold">
            </form>
        </div>
    </div>

    <form method="POST">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 text-left w-full">
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-widest">Tugaskan ke Staf (Filter: <?= $selected_category ?>)</label>
                <select name="employee_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition" required>
                    <option value="" disabled selected>-- Pilih Pegawai Pelaksana --</option>
                    <?php $employees->data_seek(0);
                    while ($emp = $employees->fetch_assoc()): ?>
                        <option value="<?= $emp['id'] ?>"><?= strtoupper($emp['nickname']) ?> (<?= $emp['name'] ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" name="bulk_assign" class="w-full md:w-auto bg-blue-600 hover:bg-slate-900 text-white font-black uppercase text-[10px] tracking-widest px-8 py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                Apply Assignment
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-center w-10">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No.</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kamar & Tamu</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis Layanan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Staf Bertugas</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status Hari Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if ($tasks->num_rows > 0): ?>
                            <?php $no = 1;
                            while ($task = $tasks->fetch_assoc()):
                                $is_done_today = ($task['daily_done'] > 0);
                            ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!$task['employee_id']): ?>
                                            <input type="checkbox" name="selected_services[]" value="<?= $task['id'] ?>" class="task-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                        <?php else: ?>
                                            <i class="fas fa-lock text-gray-200 text-xs"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-400">
                                        <?= $no++ ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col text-left">
                                            <span class="font-black text-slate-700 uppercase text-sm">Room <?= $task['room_number'] ?></span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight"><?= $task['guest_name'] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-left">
                                        <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 uppercase">
                                            <?= $task['service_name'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-left">
                                        <?php if ($task['employee_id']): ?>
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                <span class="text-xs font-black text-slate-700 uppercase"><?= $task['employee_name'] ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] font-bold text-gray-300 uppercase italic tracking-tighter text-left">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if ($is_done_today): ?>
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border bg-emerald-100 text-emerald-600 border-emerald-200">
                                                <i class="fas fa-check-double mr-1"></i> Selesai
                                            </span>
                                        <?php elseif ($task['employee_id']): ?>
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border bg-blue-100 text-blue-600 border-blue-200">
                                                On Progress
                                            </span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border bg-amber-100 text-amber-600 border-amber-200">
                                                Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <i class="fas fa-filter text-4xl text-slate-100 mb-3 block"></i>
                                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Tidak ada layanan <?= $selected_category ?> pada periode ini</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.task-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
</script>

<?php
$content = ob_get_clean();
$taskactive = 'active';
include 'layouts/app.php';
?>