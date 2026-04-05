<?php
include '../config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid");
}

$id = $_GET['id'];

// ambil data pegawai
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data tidak ditemukan");
}

$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $_POST['full_name'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $status    = $_POST['status'] ?? '';

    if (!$full_name) {
        die("<script>alert('Nama wajib diisi'); window.location='';</script>");
    }

    $stmt = $conn->prepare("UPDATE employees SET
        full_name = ?,
        phone = ?,
        status = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssi",
        $full_name,
        $phone,
        $status,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate'); window.location='pegawai.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

ob_start();
?>

<div class="container mt-5">
    <div class="flex justify-between">
        <div class="backbtn">
            <a href="pegawai.php">
                <div class="bg-blue-200 rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M20 11v2H8l5.5 5.5l-1.42 1.42L4.16 12l7.92-7.92L13.5 5.5L8 11z" />
                    </svg>
                </div>
            </a>
        </div>
        <div class="w-full max-w-xl">
            <h2 class="text-3xl font-bold mb-6">Edit Pegawai</h2>

            <form method="POST" class="bg-white shadow-md rounded-lg p-6">

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" name="full_name"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Contoh: Budi Santoso" value="<?php echo htmlspecialchars($data['full_name']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Nomor HP</label>
                    <input type="text" name="phone"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="08xxxx" value="<?php echo htmlspecialchars($data['phone']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="status" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Simpan
                </button>

            </form>
        </div>

        <div></div>
    </div>
</div>


<?php
$content = ob_get_clean();
$pegawaiactive = "active";
include 'layouts/app.php';
?>