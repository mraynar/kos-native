<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: ../auth/login.php");
    exit();
}

$pegawai_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$bulan_pilihan = $_GET['bulan'] ?? date('m');
$tahun_pilihan = $_GET['tahun'] ?? date('Y');
$limit = 10; // Tampilkan 10 data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$cek_absen = mysqli_query($conn, "SELECT * FROM absensi WHERE user_id = '$pegawai_id' AND tanggal = '$today'");
$sudah_absen = (mysqli_num_rows($cek_absen) > 0);

if (isset($_POST['submit_absen']) && !$sudah_absen) {
    $status = $_POST['status'];
    $keterangan = $_POST['keterangan'] ?? '';
    $jam = date('H:i:s');

    $target_dir = "../assets/img/absensi/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $file_ext = pathinfo($_FILES["foto_absen"]["name"], PATHINFO_EXTENSION);
    $file_name = "ABSEN_" . time() . "_" . $pegawai_id . "." . $file_ext;
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["foto_absen"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO absensi (user_id, tanggal, jam_masuk, foto_bukti, status, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $pegawai_id, $today, $jam, $file_name, $status, $keterangan);

        if ($stmt->execute()) {
            echo "<script>alert('Absensi Berhasil!'); window.location='absensi.php';</script>";
        } else {
            echo "<script>alert('Gagal Simpan Database: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Gagal Upload Foto! Cek Permission Folder.');</script>";
    }
}

ob_start();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 p-8 flex flex-col justify-between" style="min-height: 580px;">
            <div>
                <h3 class="text-xl font-black text-slate-800 uppercase mb-6 text-center tracking-tighter">Form Kehadiran</h3>

                <?php if (!$sudah_absen): ?>
                    <form method="POST" enctype="multipart/form-data" class="space-y-5">
                        <div class="relative w-full h-52 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center">
                            <img id="preview-img" class="hidden w-full h-full object-cover">
                            <div id="placeholder-icon" class="text-center">
                                <i class="fas fa-camera text-3xl text-slate-300 mb-2"></i>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ambil Foto Bukti</p>
                            </div>
                            <input type="file" name="foto_absen" id="foto_input" accept="image/*" capture="camera" required class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Tipe Kehadiran</label>
                            <select name="status" id="status_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 outline-none">
                                <option value="hadir">HADIR BEKERJA</option>
                                <option value="izin">IZIN / SAKIT</option>
                            </select>
                        </div>

                        <div id="keterangan_box" class="hidden">
                            <textarea name="keterangan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs h-24" placeholder="Tulis alasan izin..."></textarea>
                        </div>

                        <button type="submit" name="submit_absen" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-blue-200 hover:bg-slate-900 transition active:scale-95">
                            Kirim Absensi
                        </button>
                    </form>
                <?php else: ?>
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-100">
                            <i class="fas fa-check-double text-2xl"></i>
                        </div>
                        <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">Absensi Terkirim</p>
                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-tighter">Selamat Bekerja, Rekan!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden flex flex-col" style="min-height: 580px;">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Log Kehadiran Bulanan</h3>
                <form method="GET" class="flex items-center gap-2">
                    <select name="bulan" onchange="this.form.submit()" class="text-[10px] font-black border-none bg-transparent outline-none uppercase cursor-pointer">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= sprintf('%02d', $m) ?>" <?= $bulan_pilihan == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor; ?>
                    </select>
                </form>
            </div>

            <div class="flex-grow overflow-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $query_log = mysqli_query($conn, "SELECT * FROM absensi WHERE user_id = '$pegawai_id' AND MONTH(tanggal) = '$bulan_pilihan' ORDER BY tanggal DESC LIMIT $start, $limit");
                        if (mysqli_num_rows($query_log) > 0):
                            while ($row = mysqli_fetch_assoc($query_log)): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5">
                                        <p class="text-xs font-black text-slate-700"><?= date('d M Y', strtotime($row['tanggal'])) ?></p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase"><?= $row['jam_masuk'] ?></p>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="text-[9px] font-black <?= $row['status'] == 'hadir' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' ?> px-3 py-1.5 rounded-lg uppercase">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-[10px] font-medium text-slate-500 max-w-[150px] truncate"><?= $row['keterangan'] ?: '-' ?></p>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="../assets/img/absensi/<?= $row['foto_bukti'] ?>" target="_blank" class="w-8 h-8 inline-flex items-center justify-center bg-slate-100 text-slate-400 rounded-full hover:bg-blue-600 hover:text-white transition">
                                            <i class="fas fa-image text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-32 text-center">
                                    <div class="opacity-10 mb-4"><i class="fas fa-folder-open text-6xl"></i></div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum ada data absensi di bulan ini.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('foto_input').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('preview-img');
            const icon = document.getElementById('placeholder-icon');
            preview.src = reader.result;
            preview.classList.remove('hidden');
            icon.classList.add('hidden');
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    document.getElementById('status_select').addEventListener('change', function() {
        document.getElementById('keterangan_box').classList.toggle('hidden', this.value !== 'izin');
    });
</script>

<?php
$content = ob_get_clean();
$absenactive = "active";
include 'layouts/app.php';
?>