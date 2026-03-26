<?php
require_once '../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: /sewa-kos/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$alert = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $query = mysqli_query($conn, "SELECT password FROM users WHERE id = '$user_id'");
    $user = mysqli_fetch_assoc($query);

    if (!password_verify($current_password, $user['password'])) {
        $alert = ['status' => 'error', 'message' => 'Kata sandi saat ini tidak sesuai.'];
    } elseif ($new_password !== $confirm_password) {
        $alert = ['status' => 'error', 'message' => 'Konfirmasi kata sandi baru tidak cocok.'];
    } elseif (strlen($new_password) < 8) {
        $alert = ['status' => 'error', 'message' => 'Kata sandi baru minimal harus 8 karakter.'];
    } else {
        // Proses Update
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'");

        if ($update) {
            $alert = ['status' => 'success', 'message' => 'Kata sandi berhasil diperbarui!'];
        } else {
            $alert = ['status' => 'error', 'message' => 'Terjadi kesalahan sistem. Coba lagi nanti.'];
        }
    }
}
?>

<div class="flex items-center gap-4 mb-10 text-left">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Keamanan</h3>
</div>

<div class="max-w-md mx-auto lg:mx-0" x-data="{ showOld: false, showNew: false, showConfirm: false }">

    <?php if ($alert): ?>
        <div class="mb-6 p-4 rounded-2xl flex items-center gap-3 <?= $alert['status'] === 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' ?>">
            <i class="fas <?= $alert['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
            <p class="text-xs font-bold"><?= $alert['message'] ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-6 text-left">

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Kata Sandi Saat Ini</label>
            <div class="relative group">
                <input :type="showOld ? 'text' : 'password'" name="current_password" required placeholder="••••••••"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-red-500/5 focus:border-red-200 outline-none transition-all placeholder:text-slate-300">
                <button type="button" @click="showOld = !showOld" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors">
                    <i class="fas" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="py-2 flex items-center gap-4">
            <div class="h-[1px] flex-grow bg-slate-100"></div>
            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Sandi Baru</span>
            <div class="h-[1px] flex-grow bg-slate-100"></div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Kata Sandi Baru</label>
            <div class="relative group">
                <input :type="showNew ? 'text' : 'password'" name="new_password" required placeholder="••••••••"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder:text-slate-300">
                <button type="button" @click="showNew = !showNew" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors">
                    <i class="fas" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Konfirmasi Kata Sandi Baru</label>
            <div class="relative group">
                <input :type="showConfirm ? 'text' : 'password'" name="confirm_password" required placeholder="••••••••"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all placeholder:text-slate-300">
                <button type="button" @click="showConfirm = !showConfirm" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-primary transition-colors">
                    <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="p-5 bg-blue-50/50 rounded-[24px] border border-blue-100/50">
            <div class="flex gap-3">
                <i class="fas fa-shield-alt text-blue-400 mt-0.5 text-xs"></i>
                <p class="text-[10px] font-bold text-blue-600/80 leading-relaxed italic">
                    Tips: Gunakan minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka untuk keamanan yang lebih maksimal.
                </p>
            </div>
        </div>

        <button type="submit" class="w-full py-5 bg-slate-900 text-white font-black rounded-[24px] hover:bg-primary transition-all active:scale-[0.98] shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
            <i class="fas fa-key text-xs opacity-50"></i>
            <span>Perbarui Kata Sandi</span>
        </button>
    </form>
</div>