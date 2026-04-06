<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: ../auth/login.php");
    exit();
}

$pegawai_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$cek_absen = mysqli_query($conn, "SELECT * FROM absensi WHERE user_id = '$pegawai_id' AND tanggal = '$today'");
$sudah_absen = (mysqli_num_rows($cek_absen) > 0);

$tab = $_GET['tab'] ?? 'rutin';

if (isset($_POST['update_status'])) {
    $req_id = mysqli_real_escape_string($conn, $_POST['request_id']);

    $foto_nama = null;
    if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] === 0) {
        $ext = pathinfo($_FILES['bukti_foto']['name'], PATHINFO_EXTENSION);
        $foto_nama = "MAINT_" . time() . "_" . $pegawai_id . "." . $ext;

        $target_dir = "../assets/img/reports/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . $foto_nama;
        move_uploaded_file($_FILES['bukti_foto']['tmp_name'], $target_file);
    }

    $update_query = "UPDATE maintenance_requests SET status = 'done'";
    if ($foto_nama) {
        $update_query .= ", photo = '$foto_nama'";
    }
    $update_query .= " WHERE id = '$req_id' AND employee_id = '$pegawai_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Perbaikan ditandai selesai!'); window.location='maintenance.php?tab=perbaikan';</script>";
    }
}

ob_start();
?>

<div class="space-y-8 text-left">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">Maintenance Area</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kontrol Fasilitas & Perbaikan Unit</p>
        </div>

        <div class="flex gap-1 bg-white p-1 rounded-2xl shadow-sm border border-gray-100">
            <a href="?tab=rutin" class="<?= $tab == 'rutin' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50' ?> px-6 py-3 rounded-xl text-[10px] font-black uppercase transition-all">Tugas Rutin</a>
            <a href="?tab=perbaikan" class="<?= $tab == 'perbaikan' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50' ?> px-6 py-3 rounded-xl text-[10px] font-black uppercase transition-all">Laporan Kerusakan</a>
        </div>
    </div>

    <div class="relative min-h-[500px]">
        <?php if (!$sudah_absen): ?>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md z-10 flex flex-col items-center justify-center rounded-[32px] text-white p-6 text-center">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <i class="fas fa-lock text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black uppercase tracking-tighter mb-2">Akses Terkunci</h3>
                <p class="text-sm font-medium mb-8 opacity-80 max-w-xs mx-auto">Selesaikan absensi terlebih dahulu.</p>
                <a href="absensi.php" class="bg-blue-600 px-10 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest">Ke Halaman Absensi</a>
            </div>
        <?php endif; ?>

        <div class="<?= !$sudah_absen ? 'opacity-20 pointer-events-none select-none' : '' ?>">

            <?php if ($tab == 'rutin'): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
                    <?php
                    $rutinitas = [
                        ['task' => 'Sapu & Pel Area Publik', 'icon' => 'fa-broom'],
                        ['task' => 'Buang Sampah Utama', 'icon' => 'fa-trash-alt'],
                        ['task' => 'Cek Stok Air Tandon', 'icon' => 'fa-faucet'],
                        ['task' => 'Cek Lampu Koridor', 'icon' => 'fa-lightbulb'],
                        ['task' => 'Kebersihan Dapur & Kompor', 'icon' => 'fa-utensils'],
                        ['task' => 'Penyiraman Tanaman', 'icon' => 'fa-leaf']
                    ];

                    foreach ($rutinitas as $r):
                    ?>
                        <div class="bg-white p-6 rounded-[32px] border border-gray-100 flex items-center justify-between group hover:border-blue-500 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-600 transition-all">
                                    <i class="fas <?= $r['icon'] ?> text-lg"></i>
                                </div>
                                <h4 class="font-black text-slate-800 uppercase text-[11px] tracking-tighter"><?= $r['task'] ?></h4>
                            </div>
                            <button onclick="markDone(this)" class="w-10 h-10 bg-slate-50 text-slate-300 rounded-xl hover:bg-green-500 hover:text-white transition-all">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">No.</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Masalah & Lokasi</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM maintenance_requests WHERE status != 'done' AND employee_id = '$pegawai_id' ORDER BY created_at DESC");
                            $no = 1;
                            if (mysqli_num_rows($query) > 0):
                                while ($row = mysqli_fetch_assoc($query)):
                            ?>
                                    <tr class="hover:bg-gray-50/50 transition text-left">
                                        <td class="px-8 py-5 text-[11px] font-bold text-slate-400">
                                            <?= $no++ ?>
                                        </td>
                                        <td class="px-8 py-5 text-left">
                                            <p class="font-black text-slate-700 uppercase text-sm"><?= $row['issue_name'] ?></p>
                                            <p class="text-[9px] font-bold text-blue-500 uppercase tracking-widest"><?= $row['location'] ?></p>
                                            <p class="text-[11px] text-gray-400 mt-1 italic"><?= $row['description'] ?></p>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <button onclick="openMaintModal(<?= $row['id'] ?>)" class="bg-slate-900 text-white text-[9px] font-black uppercase px-6 py-3 rounded-xl hover:bg-blue-600 transition shadow-lg active:scale-95">
                                                Lapor Perbaikan
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="3" class="px-8 py-24 text-center">
                                        <div class="opacity-10 mb-4 text-center w-full"><i class="fas fa-tools text-5xl"></i></div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-full">Semua fasilitas dalam kondisi baik.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="maintModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-md p-10 shadow-2xl">
        <h3 class="text-xl font-black text-slate-800 uppercase italic mb-2 tracking-tighter text-left">Bukti Perbaikan</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-8 tracking-widest text-left">Upload foto hasil perbaikan unit</p>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="request_id" id="modal_request_id">

            <div onclick="document.getElementById('fileInput').click()" class="border-2 border-dashed border-slate-100 rounded-[24px] p-2 text-center hover:border-blue-400 transition cursor-pointer relative min-h-[220px] flex flex-col items-center justify-center overflow-hidden bg-slate-50">
                <div id="preview-placeholder" class="flex flex-col items-center py-10">
                    <i class="fas fa-camera text-4xl text-slate-200 mb-4"></i>
                    <p class="text-[9px] font-black text-slate-400 uppercase">Klik untuk Pilih Foto</p>
                </div>
                <img id="img-preview" class="hidden absolute inset-0 w-full h-full object-cover z-20">
                <input type="file" name="bukti_foto" id="fileInput" class="hidden" accept="image/*" onchange="previewImage(this)" required>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeMaintModal()" class="flex-1 py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-[9px] uppercase tracking-widest">Batal</button>
                <button type="submit" name="update_status" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black text-[9px] uppercase tracking-widest shadow-xl shadow-blue-200">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        const placeholder = document.getElementById('preview-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openMaintModal(id) {
        document.getElementById('modal_request_id').value = id;
        document.getElementById('maintModal').classList.remove('hidden');
    }

    function closeMaintModal() {
        document.getElementById('maintModal').classList.add('hidden');
        document.getElementById('img-preview').classList.add('hidden');
        document.getElementById('preview-placeholder').classList.remove('hidden');
        document.getElementById('fileInput').value = '';
    }

    function markDone(btn) {
        btn.parentElement.style.opacity = '0.4';
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.disabled = true;
        btn.classList.add('bg-green-500', 'text-white');
    }
</script>

<?php
$content = ob_get_clean();
$maintactive = "active";
include 'layouts/app.php';
?>