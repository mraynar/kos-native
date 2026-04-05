<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'pegawai'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data pegawai tidak ditemukan");
}

$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nickname  = mysqli_real_escape_string($conn, $_POST['nickname']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = $_POST['password'];

    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email' AND id != '$id'");
    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email sudah digunakan oleh akun lain!'); window.history.back();</script>";
        exit();
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET nickname = ?, phone = ?, email = ?, password = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("ssssi", $nickname, $phone, $email, $hashed_password, $id);
    } else {
        $update_query = "UPDATE users SET nickname = ?, phone = ?, email = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("sssi", $nickname, $phone, $email, $id);
    }

    if ($stmt_update->execute()) {
        echo "<script>alert('Data akun pegawai berhasil diperbarui!'); window.location='pegawai.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

ob_start();
?>

<div class="container mx-auto max-w-4xl py-10">
    <div class="flex items-start gap-8 px-4 md:px-0">
        <a href="pegawai.php" class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-blue-600 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>

        <div class="flex-1">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Akun Pegawai</h2>
                <p class="text-gray-500 mt-2 text-sm">Perbarui informasi profil atau akses login staf operasional.</p>
            </div>

            <form method="POST" class="bg-white shadow-xl shadow-gray-100 rounded-[32px] p-8 md:p-12 border border-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Panggilan</label>
                        <input type="text" name="nickname" required
                            class="w-full border border-gray-200 rounded-2xl px-6 py-4 text-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all"
                            value="<?= htmlspecialchars($data['nickname']); ?>">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nomor WhatsApp</label>
                        <input type="text" name="phone" required
                            class="w-full border border-gray-200 rounded-2xl px-6 py-4 text-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all"
                            value="<?= htmlspecialchars($data['phone']); ?>">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Email Login</label>
                        <input type="email" name="email" required
                            class="w-full border border-gray-200 rounded-2xl px-6 py-4 text-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all"
                            value="<?= htmlspecialchars($data['email']); ?>">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Reset Password (Opsional)</label>
                        <input type="password" name="password"
                            class="w-full border border-gray-200 rounded-2xl px-6 py-4 text-sm focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 outline-none transition-all placeholder:text-gray-300"
                            placeholder="Kosongkan jika tidak ingin ganti">
                    </div>

                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-5 rounded-2xl hover:bg-gray-900 transition-all shadow-xl shadow-blue-200">
                        Simpan Perubahan
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-8 uppercase tracking-widest font-black italic">
                        ID Pegawai: #<?= $data['id'] ?> — Terdaftar sejak <?= date('d M Y', strtotime($data['created_at'] ?? 'now')) ?>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pegawaiactive = "active";
include 'layouts/app.php';
?>