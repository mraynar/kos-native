<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: ../auth/login.php");
    exit();
}

$pegawai_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$pegawai_id'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update_profile'])) {
    $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET nickname = '$nickname', password = '$hashed' WHERE id = '$pegawai_id'");
    } else {
        $update = mysqli_query($conn, "UPDATE users SET nickname = '$nickname' WHERE id = '$pegawai_id'");
    }

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil: " . mysqli_error($conn) . "');</script>";
    }
}

ob_start();
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-10">
            <div class="flex items-center gap-6 mb-10">
                <div class="w-24 h-24 bg-blue-600 rounded-3xl flex items-center justify-center text-white text-4xl shadow-xl shadow-blue-200">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">
                        <?= $data['full_name_ktp'] ?? $data['nickname']; ?>
                    </h3>
                    <p class="text-sm font-bold text-blue-500 uppercase tracking-widest italic">Staff Operasional Griya Asri</p>
                </div>
            </div>

            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Utama (Read Only)</label>
                    <input type="text" value="<?= $data['email'] ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold text-slate-400 cursor-not-allowed" readonly>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-700 uppercase tracking-widest ml-1 text-blue-600">Nama Panggilan</label>
                    <input type="text" name="nickname" value="<?= $data['nickname'] ?>" class="w-full border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition" required>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-gray-700 uppercase tracking-widest ml-1">Ganti Password</label>
                    <input type="password" name="password" class="w-full border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition" placeholder="Masukkan password baru jika ingin mengganti">
                </div>

                <div class="md:col-span-2 pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" name="update_profile" class="flex-[2] bg-blue-600 text-white font-black uppercase text-[10px] tracking-widest py-5 rounded-2xl shadow-xl shadow-blue-200 hover:bg-slate-900 transition active:scale-95">
                        Simpan Perubahan Profil
                    </button>

                    <a href="../auth/logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="flex-1 bg-red-50 text-red-600 font-black uppercase text-[10px] tracking-widest py-5 rounded-2xl border border-red-100 text-center hover:bg-red-600 hover:text-white transition active:scale-95">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$profileactive = "active";
include 'layouts/app.php';
?>