<?php
include '../config/database.php';
session_start();

if ($_SESSION['role'] !== 'pegawai') {
    header("Location: ../auth/login.php");
    exit();
}

$pegawai_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$cek_absen = mysqli_query($conn, "SELECT * FROM absensi WHERE user_id = '$pegawai_id' AND tanggal = '$today'");
$sudah_absen = (mysqli_num_rows($cek_absen) > 0);

$filter = $_GET['category'] ?? 'Semua';

ob_start();
?>

<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tugas Harian Pegawai</h1>
            <p class="text-sm text-gray-500 font-medium italic">Update laporan harian untuk setiap layanan aktif.</p>
        </div>
        <div class="flex gap-2 bg-white p-1 rounded-xl shadow-sm border border-gray-100">
            <a href="?category=Semua" class="<?= $filter == 'Semua' ? 'bg-blue-600 text-white' : 'text-gray-500' ?> px-4 py-2 rounded-lg text-[10px] font-black uppercase transition">Semua</a>
            <a href="?category=Catering" class="<?= $filter == 'Catering' ? 'bg-blue-600 text-white' : 'text-gray-500' ?> px-4 py-2 rounded-lg text-[10px] font-black uppercase transition">Catering</a>
            <a href="?category=Laundry" class="<?= $filter == 'Laundry' ? 'bg-blue-600 text-white' : 'text-gray-500' ?> px-4 py-2 rounded-lg text-[10px] font-black uppercase transition">Laundry</a>
            <a href="?category=Cleaning" class="<?= $filter == 'Cleaning' ? 'bg-blue-600 text-white' : 'text-gray-500' ?> px-4 py-2 rounded-lg text-[10px] font-black uppercase transition">Cleaning</a>
        </div>
    </div>

    <div class="relative">

        <?php if (!$sudah_absen): ?>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md z-10 flex flex-col items-center justify-center rounded-[32px] text-white p-6 text-center transition-all duration-500">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <i class="fas fa-lock text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold uppercase tracking-tighter mb-2">Akses Terkunci</h3>
                <p class="text-sm font-medium mb-8 opacity-80 max-w-xs mx-auto">
                    Anda harus melakukan absensi harian terlebih dahulu sebelum dapat mengakses daftar tugas dan laporan.
                </p>
                <a href="absensi.php" class="bg-blue-600 hover:bg-white hover:text-blue-600 px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-2xl shadow-blue-500/50 transition-all active:scale-95">
                    Ke Halaman Absensi
                </a>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden <?= !$sudah_absen ? 'opacity-20 pointer-events-none select-none' : '' ?>">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">No.</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Penyewa & Kamar</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Layanan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jadwal Tugas</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Hari Ini</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php
                    $where = "WHERE bs.employee_id = '$pegawai_id'";
                    if ($filter !== 'Semua') $where .= " AND s.service_name LIKE '%$filter%'";

                    $query = mysqli_query($conn, "
                        SELECT bs.*, b.check_in, b.check_out, r.room_number, s.service_name, u.full_name_ktp,
                        (SELECT COUNT(*) FROM service_reports WHERE booking_service_id = bs.id AND report_date = '$today') as is_done
                        FROM booking_service bs
                        JOIN bookings b ON bs.booking_id = b.id
                        JOIN rooms r ON b.room_id = r.id
                        JOIN additional_services s ON bs.additional_service_id = s.id
                        JOIN users u ON b.user_id = u.id
                        $where
                        ORDER BY bs.service_status ASC, b.check_out DESC
                    ");

                    $no = 1;
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)):
                            $display_date = ($row['is_done'] > 0) ? date('Y-m-d', strtotime('+1 day')) : $today;
                    ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-5 text-[11px] font-bold text-slate-400">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-5 text-left">
                                    <p class="font-black text-slate-700 uppercase text-sm">Kamar <?= $row['room_number'] ?></p>
                                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-tight"><?= $row['full_name_ktp'] ?></p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-[10px] font-black bg-slate-100 px-3 py-1.5 rounded-lg text-slate-600 uppercase">
                                        <?= $row['service_name'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-slate-800"><?= date('d M Y', strtotime($display_date)) ?></p>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter italic text-left">
                                        <?= ($row['is_done'] > 0) ? 'Next Schedule' : 'Current Schedule' ?>
                                    </p>
                                </td>
                                <td class="px-6 py-5">
                                    <?php if ($row['is_done'] > 0): ?>
                                        <span class="text-[9px] font-black bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full uppercase border border-emerald-200">Selesai</span>
                                    <?php else: ?>
                                        <span class="text-[9px] font-black bg-amber-100 text-amber-600 px-4 py-1.5 rounded-full uppercase border border-amber-200">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if ($row['is_done'] == 0 && $row['service_status'] != 'done'): ?>
                                        <button onclick="openModal(<?= $row['id'] ?>)" class="bg-blue-600 hover:bg-slate-900 text-white text-[9px] font-black uppercase px-5 py-2.5 rounded-xl transition shadow-lg shadow-blue-200 active:scale-95">
                                            Lapor Tugas
                                        </button>
                                    <?php elseif ($row['service_status'] == 'done'): ?>
                                        <div class="flex items-center justify-center text-slate-400 gap-2">
                                            <i class="fas fa-archive text-sm"></i>
                                            <span class="text-[9px] font-black uppercase">Service Expired</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center justify-center text-emerald-500 gap-2">
                                            <i class="fas fa-check-double text-sm"></i>
                                            <span class="text-[9px] font-black uppercase">Terkirim</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile;
                    } else { ?>
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-check text-slate-200 text-2xl"></i>
                                </div>
                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest text-center w-full">Tidak ada daftar tugas untuk saat ini</p>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="uploadModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl">
        <h3 class="text-xl font-black text-slate-800 mb-1 uppercase italic">Bukti Pengerjaan</h3>
        <p class="text-xs text-gray-400 mb-6 font-bold uppercase tracking-tight">Hari Ini: <?= date('d M Y') ?></p>

        <form action="proses-laporan.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="service_id" id="modal_service_id">
            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center hover:border-blue-400 transition group">
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-200 mb-3 group-hover:text-blue-400"></i>
                <p class="text-[10px] font-black text-gray-400 uppercase mb-4">Pilih Foto Bukti</p>
                <input type="file" name="bukti_foto" class="text-[10px] font-bold text-gray-500 ml-10" required>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 rounded-2xl text-[10px] font-black uppercase text-slate-400">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-blue-600 rounded-2xl text-[10px] font-black uppercase text-white shadow-xl shadow-blue-200 active:scale-95 transition">Kirim Bukti</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('modal_service_id').value = id;
        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }
</script>

<?php
$content = ob_get_clean();
$tugasactive = "active";
include 'layouts/app.php';
?>