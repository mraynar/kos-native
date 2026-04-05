<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

function getSettingValue($conn, $key)
{
    $stmt = $conn->prepare("SELECT value FROM settings WHERE `key` = ?");
    if ($stmt) {
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['value'] ?? 'Griya Asri Kos';
    }
    return 'Griya Asri Kos';
}

$query_admin = $conn->prepare("SELECT name, nickname, email FROM users WHERE id = ?");
$query_admin->bind_param("i", $admin_id);
$query_admin->execute();
$admin_data = $query_admin->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_title = mysqli_real_escape_string($conn, $_POST['site_title']);
    $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
    $new_password = $_POST['new_password'];

    $stmt_set = $conn->prepare("INSERT INTO settings (`key`, `value`) VALUES ('site_title', ?) ON DUPLICATE KEY UPDATE value=?");
    $stmt_set->bind_param("ss", $site_title, $site_title);
    $stmt_set->execute();

    $stmt_pref = $conn->prepare("UPDATE users SET nickname = ? WHERE id = ?");
    $stmt_pref->bind_param("si", $nickname, $admin_id);
    $stmt_pref->execute();
    $_SESSION['nickname'] = $nickname;

    if (!empty($new_password)) {
        $hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt_pass->bind_param("si", $hashed_pass, $admin_id);
        $stmt_pass->execute();
    }

    echo "<script>alert('Berhasil disimpan!'); window.location='profile.php';</script>";
    exit;
}

ob_start();
?>

<div class="container mx-auto px-4 py-8 text-left">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-sm text-gray-500">Update akun dan identitas website</p>
        </div>
        <a href="../auth/logout.php" onclick="return confirm('Keluar?')" class="bg-red-50 text-red-600 px-6 py-2 rounded-xl font-bold text-sm border border-red-100 shadow-sm">
            <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">
        <form method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Email (Read Only)</label>
                    <input type="text" value="<?= $admin_data['email'] ?>" disabled class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm font-bold text-gray-400 cursor-not-allowed">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Nickname</label>
                    <input type="text" name="nickname" value="<?= htmlspecialchars($admin_data['nickname']) ?>" required class="w-full border border-gray-200 rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/5 outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block ml-1">Site Title</label>
                <input type="text" name="site_title" value="<?= htmlspecialchars(getSettingValue($conn, 'site_title')) ?>" required class="w-full border border-gray-200 rounded-2xl px-6 py-4 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/5 outline-none">
            </div>

            <div class="bg-slate-50 p-8 rounded-[32px] border border-slate-100">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block ml-1 mb-4 text-left">Ganti Password</label>
                <input type="password" name="new_password" placeholder="Isi hanya jika ingin mengganti password" class="w-full bg-white border border-slate-200 rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-500/5 outline-none">
            </div>

            <div class="flex justify-end border-t border-gray-50 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-slate-900 text-white font-black py-4 px-12 rounded-2xl shadow-xl shadow-blue-200 text-xs uppercase tracking-widest transition-all active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
$profileactive = "active";
include 'layouts/app.php';
?>