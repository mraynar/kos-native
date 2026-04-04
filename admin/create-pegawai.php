<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $_POST['full_name'] ?? '';
    $salary    = $_POST['salary'] ?? 0;
    $phone     = $_POST['phone'] ?? '';
    $address   = $_POST['address'] ?? '';
    $position  = $_POST['position'] ?? '';

    $status = 'inactive';

    if (!$full_name || !$salary || !$phone || !$address || !$position) {
        die("<script>alert('Semua field wajib diisi'); window.location='';</script>");
    }

    // AUTO GENERATE KODE
    $result = $conn->query("SELECT MAX(id) as last_id FROM employees");
    $row = $result->fetch_assoc();
    $next_id = $row['last_id'] + 1;

    $employee_code = 'EMP-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare("INSERT INTO employees (
        employee_code,
        full_name,
        salary,
        phone,
        address,
        position,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssissss",
        $employee_code,
        $full_name,
        $salary,
        $phone,
        $address,
        $position,
        $status
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan'); window.location='pegawai.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
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
                    <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" placeholder="Contoh : John Doe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="full_name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Gaji</label>
                    <input type="number" placeholder="Contoh : 3500000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="salary" required>
                </div>


                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                    <input type="text" placeholder="Contoh : 0821" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="phone" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                    <input type="text" placeholder="Contoh : Jl Ketintang" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="address" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Posisi</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="position" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="Admin Operasional">Admin Operasional</option>
                        <option value="Cleaning Service">Cleaning Service</option>
                        <option value="Penjaga Kos">Penjaga Kos</option>
                        <option value="Maintenance">Maintenance</option>
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