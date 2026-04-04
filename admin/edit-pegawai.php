<?php
include '../config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data tidak ditemukan");
}

$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status       = $_POST['status'];
    $full_name    = $_POST['full_name'];
    $salary       = $_POST['salary'];
    $phone        = $_POST['phone'];
    $address      = $_POST['address'];
    $position     = $_POST['position'];

    $stmt = $conn->prepare("UPDATE employees SET
        status = ?,
        full_name = ?,
        salary = ?,
        phone = ?,
        address = ?,
        position = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssisssi",
        $status,
        $full_name,
        $salary,
        $phone,
        $address,
        $position,
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
        <div class="w-full max-w-2xl">
            <h2 class="text-3xl font-bold mb-6">Edit Data Pegawai</h2>
            <form method="POST" action="" class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="status" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kode</label>
                    <input type="text" disabled value="<?= $data['employee_code'] ?>" placeholder="Contoh : EMP-001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="employee_code">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" value="<?= $data['full_name'] ?>" placeholder="Contoh : John Doe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="full_name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gaji</label>
                    <input type="number" value="<?= $data['salary'] ?>" placeholder="Contoh : 3500000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="salary" required>
                </div>


                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                    <input type="text" value="<?= $data['phone'] ?>" placeholder="Contoh : 0821" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="phone" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                    <input type="text" value="<?= $data['address'] ?>" placeholder="Contoh : Jl Ketintang" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="address" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Posisi</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="position" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="Admin Operasional" <?= $data['position'] == 'Admin Operasional' ? 'selected' : '' ?>>Admin Operasional</option>
                        <option value="Cleaning Service" <?= $data['position'] == 'Cleaning Service' ? 'selected' : '' ?>>Cleaning Service</option>
                        <option value="Penjaga Kos" <?= $data['position'] == 'Penjaga Kos' ? 'selected' : '' ?>>Penjaga Kos</option>
                        <option value="Maintenance" <?= $data['position'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Simpan Data</button>
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