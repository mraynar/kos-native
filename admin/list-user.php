<?php
include '../config/database.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM users WHERE role = 'penyewa'";

if (!empty($search)) {
    $sql .= " AND (full_name_ktp LIKE ? OR email LIKE ? OR phone LIKE ?)";
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
    <div class="header-content flex justify-between mb-4 items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pengguna (Penyewa)</h1>
            <?php if (!empty($search)): ?>
                <p class="text-sm text-gray-600 mt-1">
                    Hasil pencarian untuk: <b><?= htmlspecialchars($search) ?></b>
                </p>
            <?php endif; ?>
        </div>

        <form method="GET" class="flex gap-2">
            <input
                type="text"
                name="search"
                placeholder="Cari Nama, Email, atau HP..."
                value="<?= htmlspecialchars($search) ?>"
                class="border px-4 py-2 rounded-lg w-64 focus:ring-2 focus:ring-blue-500 outline-none text-sm shadow-sm">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold transition-all shadow-md">
                Cari
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 text-left">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Nama (KTP)</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">No Telp</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tgl Lahir</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center text-sm text-gray-600"><?= $no++ ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900"><?= htmlspecialchars($row['full_name_ktp'] ?? 'Belum Verifikasi') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate"><?= htmlspecialchars($row['address'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= (!empty($row['birth_date'])) ? date('d/m/Y', strtotime($row['birth_date'])) : '-' ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                $status = $row['is_verified'] ?? 'unverified';
                                $bg = 'bg-red-100 text-red-700';
                                if ($status === 'verified') $bg = 'bg-green-100 text-green-700';
                                if ($status === 'pending') $bg = 'bg-yellow-100 text-yellow-700';
                                ?>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?= $bg ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='py-20 text-center text-gray-400 font-medium italic'>Tidak ada data penyewa yang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
$useractive = "active";
include 'layouts/app.php';
$conn->close();
?>