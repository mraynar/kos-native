<?php
include '../config/database.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT 
            bookings.*,
            users.full_name_ktp AS user_name,
            rooms.room_number
        FROM bookings
        JOIN users ON bookings.user_id = users.id
        JOIN rooms ON bookings.room_id = rooms.id";

if (!empty($search)) {
    $sql .= " WHERE bookings.id LIKE ? OR users.full_name_ktp LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $param = "%" . $search . "%";
    $stmt->bind_param("ss", $param, $param);
}

$stmt->execute();
$result = $stmt->get_result();

ob_start();
?>

<div class="">
    <div class="header-content flex items-center justify-between mb-4 align-middle">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pesanan</h1>
        <?php if (!empty($search)): ?>
            <p class="text-sm text-gray-600">
                Hasil pencarian untuk: <b><?= htmlspecialchars($search) ?></b>
            </p>
        <?php endif; ?>
        <form method="GET" class="mb-4 flex gap-2">
            <input
                type="text"
                name="search"
                placeholder="Cari ID atau Nama..."
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                class="border px-3 py-2 rounded w-64">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Cari
            </button>
        </form>
    </div>
    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-center">No</th>
                <th class="border border-gray-300 px-4 py-2 text-left">ID Pesanan</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Pemesan</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Room</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Check In</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Check Out</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Total Harga</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                <!-- <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='hover:bg-gray-100 border-b border-gray-300'>";
                    echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . $no++ . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['user_name']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['room_number']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['check_in']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['check_out']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>Rp" . number_format($row['total_price'], 0, ',', '.') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'><span class='px-2 py-1 rounded text-sm " . ($row['status'] === 'paid' ? 'bg-green-200 text-green-800' : ($row['status'] === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800')) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data pesanan</td></tr>";
            }
            ?>
</div>

<?php
$content = ob_get_clean();
$pesananactive = "active";
include 'layouts/app.php';
?>

<?php
$conn->close();
?>