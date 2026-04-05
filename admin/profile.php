<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $site_title = $_POST['site_title'] ?? '';

    if (!empty($site_title)) {

        $stmt = $conn->prepare("
            INSERT INTO settings (`key`, `value`)
            VALUES ('site_title', ?)
            ON DUPLICATE KEY UPDATE value=?
        ");

        $stmt->bind_param("ss", $site_title, $site_title);
        $stmt->execute();

        echo "<script>alert('Data berhasil disimpan'); window.location='profile.php';</script>";
        exit;
    }
}

ob_start();
?>

<div class="">
    <form method="POST" action="" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Judul Website
            </label>
            <input
                type="text"
                name="site_title"
                value="<?= getSetting($conn, 'site_title'); ?>"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Masukkan judul website" required>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg transition">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<?php
$content = ob_get_clean();
$profileactive = "active";
include 'layouts/app.php';
?>