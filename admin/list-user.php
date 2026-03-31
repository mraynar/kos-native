<?php
include '../config/database.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM users";

if (!empty($search)) {
    $sql .= " WHERE full_name_ktp LIKE ? OR email LIKE ? OR phone LIKE ?";
}

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $param = "%" . $search . "%";
    $stmt->bind_param("sss", $param, $param, $param);
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
                <th class="border border-gray-300 px-4 py-2 text-left">Nama</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Alamat</th>
                <th class="border border-gray-300 px-4 py-2 text-left">No Telp</th>
                <th class="border border-gray-300 px-4 py-2 text-left">TTL</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
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
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['full_name_ktp'] ?? '') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['address'] ?? '') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['phone'] ?? '') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['birth_date'] ?? '') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'><span class='px-2 py-1 rounded text-sm " . ($row['is_verified'] === 'verified' ? 'bg-green-200 text-green-800' : ($row['is_verified'] === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800')) . "'>" . htmlspecialchars($row['is_verified'] ?? '') . "</span></td>";
                    // echo "<td class='border border-gray-300 px-4 py-2 flex gap-2 justify-center'>";
                    // echo "<a href='edit-properti.php?id=" . htmlspecialchars($row['id']) . "' class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm w-16 text-center'>Edit</a>";
                    // echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data Pengguna</td></tr>";
            }
            ?>
</div>

<?php
$content = ob_get_clean();
$useractive = "active";
include 'layouts/app.php';
?>

<?php
$conn->close();
?>