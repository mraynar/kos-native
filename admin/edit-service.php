<?php
include '../config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID tidak ditemukan");
}

$query = $conn->prepare("SELECT * FROM additional_services WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $service_name = $_POST['service_name'];
    $duration_type = $_POST['duration_type'];
    $service_price = $_POST['service_price'];

    $stmt = $conn->prepare("
        UPDATE additional_services 
        SET service_name=?, duration_type=?, service_price=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssii",
        $service_name,
        $duration_type,
        $service_price,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate'); window.location='list-service.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    exit;
}

ob_start();
?>

<div class="container mt-5">
    <div class="flex justify-between">
        <div class="backbtn">
            <a href="list-service.php">
                <div class="bg-blue-200 rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M20 11v2H8l5.5 5.5l-1.42 1.42L4.16 12l7.92-7.92L13.5 5.5L8 11z" />
                    </svg>
                </div>
            </a>
        </div>
        <div class="w-full max-w-2xl">
            <h2 class="text-3xl font-bold mb-6">Edit Service</h2>
            <form method="POST" action="" class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Service</label>
                    <input type="text" value="<?= $data['service_name']; ?>" placeholder="Contoh : Laundry Express" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="service_name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Duration Type</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="duration_type" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="Harian" <?= $data['duration_type'] == 'Harian' ? 'selected' : '' ?>>Harian</option>
                        <option value="Mingguan" <?= $data['duration_type'] == 'Mingguan' ? 'selected' : '' ?>>Mingguan</option>
                        <option value="Bulanan" <?= $data['duration_type'] == 'Bulanan' ? 'selected' : '' ?>>Bulanan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Harga Service</label>
                    <input type="number" value="<?= $data['service_price']; ?>" placeholder="Contoh : 50000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="service_price" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Simpan Service</button>
            </form>
        </div>
        <div></div>
    </div>
</div>


<?php
$content = ob_get_clean();
$serviceactive = "active";
include 'layouts/app.php';
?>