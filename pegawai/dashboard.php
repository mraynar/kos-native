<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: ../auth/login.php");
    exit();
}

$pegawai_id = $_SESSION['user_id'];

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$servPending = $conn->query("SELECT count(*) as total FROM booking_service WHERE service_status != 'done' AND employee_id = '$pegawai_id'")->fetch_assoc()['total'] ?? 0;
$servDone = $conn->query("SELECT count(*) as total FROM booking_service WHERE service_status = 'done' AND employee_id = '$pegawai_id'")->fetch_assoc()['total'] ?? 0;

$maintPending = 0;
$maintDone = 0;
$maintCheck = $conn->query("SHOW COLUMNS FROM maintenance_requests LIKE 'employee_id'");
if ($maintCheck->num_rows > 0) {
    $maintPending = $conn->query("SELECT count(*) as total FROM maintenance_requests WHERE status != 'done' AND employee_id = '$pegawai_id'")->fetch_assoc()['total'] ?? 0;
    $maintDone = $conn->query("SELECT count(*) as total FROM maintenance_requests WHERE status = 'done' AND employee_id = '$pegawai_id'")->fetch_assoc()['total'] ?? 0;
}

$tugasHarianTotal = 6;
$tugasHarianDone = 0;
$tugasHarianPending = $tugasHarianTotal - $tugasHarianDone;

$total_data_query = mysqli_query($conn, "SELECT id FROM booking_service WHERE employee_id = '$pegawai_id' AND service_status != 'done'");
$total_rows = mysqli_num_rows($total_data_query);
$total_pages = ceil($total_rows / $limit);

ob_start();
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 border border-gray-50 text-left">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Tugas Layanan</p>
                <p class="text-2xl font-bold text-gray-900 mb-2"><?= $servPending + $servDone ?> Total</p>
                <div class="flex gap-3">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-500 uppercase">Belum</span>
                        <span class="text-xs font-bold text-slate-700"><?= $servPending ?></span>
                    </div>
                    <div class="w-[1px] h-6 bg-slate-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-emerald-500 uppercase">Selesai</span>
                        <span class="text-xs font-bold text-slate-700"><?= $servDone ?></span>
                    </div>
                </div>
            </div>
            <i class="fas fa-concierge-bell text-3xl text-blue-500 opacity-20"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border border-gray-50 text-left">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Tugas Maintenance</p>
                <p class="text-2xl font-bold text-gray-900 mb-2"><?= $maintPending + $maintDone ?> Laporan</p>
                <div class="flex gap-3">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-500 uppercase">Belum</span>
                        <span class="text-xs font-bold text-slate-700"><?= $maintPending ?></span>
                    </div>
                    <div class="w-[1px] h-6 bg-slate-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-emerald-500 uppercase">Selesai</span>
                        <span class="text-xs font-bold text-slate-700"><?= $maintDone ?></span>
                    </div>
                </div>
            </div>
            <i class="fas fa-tools text-3xl text-red-500 opacity-20"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border border-gray-50 text-left">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Tugas Harian Rutin</p>
                <p class="text-2xl font-bold text-gray-900 mb-2"><?= $tugasHarianTotal ?> Checklist</p>
                <div class="flex gap-3">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-500 uppercase">Sisa</span>
                        <span class="text-xs font-bold text-slate-700"><?= $tugasHarianPending ?></span>
                    </div>
                    <div class="w-[1px] h-6 bg-slate-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-emerald-500 uppercase">Berhasil</span>
                        <span class="text-xs font-bold text-slate-700"><?= $tugasHarianDone ?></span>
                    </div>
                </div>
            </div>
            <i class="fas fa-tasks text-3xl text-emerald-500 opacity-20"></i>
        </div>
    </div>
</div>

<div class="mt-10 text-left">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Jadwal Tugas Mendatang</h3>
        </div>
        <a href="tugas-layanan.php" class="text-xs font-bold text-blue-600 hover:text-slate-900 transition">Lihat Semua Tugas &rarr;</a>
    </div>

    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">No.</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit Kamar</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Penyewa</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis Layanan</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mulai Service</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Selesai Service</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php
                $latest_tasks = mysqli_query($conn, "
        SELECT bs.*, b.check_in, b.check_out, r.room_number, s.service_name, u.full_name_ktp as penyewa
        FROM booking_service bs
        JOIN bookings b ON bs.booking_id = b.id
        JOIN rooms r ON b.room_id = r.id
        JOIN additional_services s ON bs.additional_service_id = s.id
        JOIN users u ON b.user_id = u.id
        WHERE bs.employee_id = '$pegawai_id'
        AND bs.service_status != 'done'
        ORDER BY b.check_in ASC LIMIT $start, $limit
    ");

                $no = $start + 1;
                if (mysqli_num_rows($latest_tasks) > 0) {
                    while ($row = mysqli_fetch_assoc($latest_tasks)): ?>
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-5 text-[11px] font-bold text-slate-400">
                                <?= $no++ ?>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition">
                                        <i class="fas fa-door-open text-xs text-slate-400 group-hover:text-blue-600"></i>
                                    </div>
                                    <span class="font-black text-slate-700 uppercase">Kamar <?= $row['room_number'] ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-left">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-700 uppercase tracking-tight"><?= $row['penyewa'] ?></span>
                                    <span class="text-[9px] font-bold text-blue-500 uppercase">Penyewa Aktif</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-[11px] font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 uppercase">
                                    <?= $row['service_name'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-bold text-slate-700"><?= date('d M Y', strtotime($row['check_in'])) ?></span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase">Mulai</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-bold text-red-600"><?= date('d M Y', strtotime($row['check_out'])) ?></span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase">Berakhir</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="tugas-layanan.php" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 rounded-full hover:bg-slate-900 hover:text-white transition shadow-sm">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile;
                } else { ?>
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <i class="fas fa-calendar-day text-4xl text-slate-100 mb-3 block"></i>
                            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Tidak ada jadwal tugas aktif</p>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
            <div class="bg-slate-50/50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Halaman <?= $page ?> dari <?= $total_pages ?></p>
                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-black transition-all <?= ($page == $i) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-400 hover:bg-slate-100 border border-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$indexactive = "active";
include 'layouts/app.php';
?>