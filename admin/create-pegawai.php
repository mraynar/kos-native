<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $_POST['full_name'] ?? '';
    $phone     = $_POST['phone'] ?? '';

    if (!$full_name) {
        die("<script>alert('Nama wajib diisi'); window.location='';</script>");
    }

    $status = 'active';

    // ambil tahun sekarang (2 digit)
    $year = date('y'); // contoh: 26

    // cari nomor urut terakhir di tahun ini
    $query = $conn->prepare("
        SELECT employee_code 
        FROM employees 
        WHERE employee_code LIKE CONCAT('EMP-', ?, '-%') 
        ORDER BY employee_code DESC 
        LIMIT 1
    ");
    $query->bind_param("s", $year);
    $query->execute();
    $result = $query->get_result();

    $next_number = 1;

    if ($row = $result->fetch_assoc()) {
        // ambil angka terakhir
        $last_code = $row['employee_code']; // EMP-26-005
        $last_number = (int) substr($last_code, -3);
        $next_number = $last_number + 1;
    }

    // format final
    $employee_code = 'EMP-' . $year . '-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);

    // insert
    $stmt = $conn->prepare("INSERT INTO employees (
        employee_code,
        full_name,
        phone,
        status
    ) VALUES (?, ?, ?, ?)");

    $stmt->bind_param(
        "ssss",
        $employee_code,
        $full_name,
        $phone,
        $status
    );

    if ($stmt->execute()) {
        echo "<script>alert('Pegawai berhasil ditambahkan'); window.location='pegawai.php';</script>";
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
        <div class="w-full max-w-xl">
            <h2 class="text-3xl font-bold mb-6">Tambah Pegawai</h2>

            <form method="POST" class="bg-white shadow-md rounded-lg p-6">

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" name="full_name"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Nomor HP</label>
                    <input type="text" name="phone"
                        class="w-full border rounded-lg px-4 py-2"
                        placeholder="08xxxx">
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