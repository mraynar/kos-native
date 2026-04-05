<?php
include '../config/database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data tidak ditemukan");
}

$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $room_type_id = $_POST['room_type_id'];
    $gender_type  = $_POST['gender_type'];
    $price        = $_POST['price'];
    $facilities   = $_POST['facilities'];
    $area_size    = $_POST['area_size'];
    $is_electric_included  = $_POST['is_electric_included'];
    $is_water_included     = $_POST['is_water_included'];
    $room_rules   = $_POST['room_rules'];

    $prefix_map = [
        1 => 'H',
        2 => 'S',
        3 => 'N',
        4 => 'L'
    ];

    $prefix = $prefix_map[$room_type_id];

    $query = "SELECT room_number FROM rooms
          WHERE room_number LIKE '$prefix%' 
          ORDER BY room_number DESC LIMIT 1";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $last = $result->fetch_assoc()['room_number'];

        $num = (int) substr($last, 1);
        $num++;
    } else {
        $num = 1;
    }

    $room_number = $prefix . str_pad($num, 2, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare("UPDATE rooms SET
        status = ?,
        room_number = ?,
        room_type_id = ?,
        gender_type = ?,
        price = ?,
        facilities = ?,
        area_size = ?,
        is_electric_included = ?,
        is_water_included = ?,
        room_rules = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssisdsdissi",
        $status,
        $room_number,
        $room_type_id,
        $gender_type,
        $price,
        $facilities,
        $area_size,
        $is_electric_included,
        $is_water_included,
        $room_rules,
        $id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate'); window.location='list-properti.php';</script>";
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
            <a href="list-properti.php">
                <div class="bg-blue-200 rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M20 11v2H8l5.5 5.5l-1.42 1.42L4.16 12l7.92-7.92L13.5 5.5L8 11z" />
                    </svg>
                </div>
            </a>
        </div>
        <div class="w-full max-w-2xl">
            <h2 class="text-3xl font-bold mb-6">Edit Data Kamar</h2>
            <form method="POST" action="" class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="status" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="available" <?= $data['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="maintenance" <?= $data['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="occupied" <?= $data['status'] == 'occupied' ? 'selected' : '' ?>>Occupied</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tipe Kamar</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="room_type_id" required>
                        <option selected disabled>-- Pilih Tipe Kamar --</option>
                        <option value="1" <?= $data['room_type_id'] == 1 ? 'selected' : '' ?>>Hemat</option>
                        <option value="2" <?= $data['room_type_id'] == 2 ? 'selected' : '' ?>>Santai</option>
                        <option value="3" <?= $data['room_type_id'] == 3 ? 'selected' : '' ?>>Nyaman</option>
                        <option value="4" <?= $data['room_type_id'] == 4 ? 'selected' : '' ?>>Luas</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tipe Gender</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="gender_type" required>
                        <option selected disabled>-- Pilih Tipe Gender --</option>
                        <option value="Putra" <?= $data['gender_type'] == 'Putra' ? 'selected' : '' ?>>Putra</option>
                        <option value="Putri" <?= $data['gender_type'] == 'Putri' ? 'selected' : '' ?>>Putri</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Harga</label>
                    <input type="number" value="<?= $data['price'] ?>" placeholder="Contoh : 350000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="price" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Fasilitas</label>
                    <input type="text" value="<?= $data['facilities'] ?>" placeholder=" Contoh : Bed, Lemari, Meja Belajar" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="facilities" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Ukuran Kamar (m)</label>
                    <input type="text" value="<?= $data['area_size'] ?>" placeholder=" Contoh : 4x6" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="area_size" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Listrik</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="is_electric_included" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="0" <?= $data['is_electric_included'] == 0 ? 'selected' : '' ?>>Token</option>
                        <option value="1" <?= $data['is_electric_included'] == 1 ? 'selected' : '' ?>>Tidak Ada</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Air</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="is_water_included" required>
                        <option selected disabled>-- Pilih --</option>
                        <option value="0" <?= $data['is_water_included'] == 0 ? 'selected' : '' ?>>Include</option>
                        <option value="1" <?= $data['is_water_included'] == 1 ? 'selected' : '' ?>>Tidak Ada</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Peraturan Kamar</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="room_rules" rows="4"><?= $data['room_rules'] ?></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Simpan Kamar</button>
            </form>
        </div>
        <div></div>
    </div>
</div>


<?php
$content = ob_get_clean();
$propertiactive = "active";
include 'layouts/app.php';
?>