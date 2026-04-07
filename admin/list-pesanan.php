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

<div class="header-content flex justify-between mb-4 align-middle">
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

<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 text-left">
    <table class="w-full border-collapse">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID Pesanan</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Pemesan</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Room</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Check In</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Check Out</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Total Harga</th>
                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                <!-- <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th> -->
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='hover:bg-gray-50 transition-colors'>";
                    echo "<td class='px-6 py-4 text-center text-sm text-gray-600'>" . $no++ . "</td>";
                    echo "<td class='px-6 py-4 text-sm font-bold text-gray-600'>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['user_name']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm font-bold text-gray-600'>" . htmlspecialchars($row['room_number']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['check_in']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['check_out']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>Rp" . number_format($row['total_price'], 0, ',', '.') . "</td>";
                    echo "<td class='px-6 py-4 text-center text-sm text-gray-600'><span class='px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest " . ($row['status'] === 'paid' ? 'bg-green-100 text-green-700' : ($row['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='py-3' style='text-align:center;'>Tidak ada data pesanan</td></tr>";
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