<?php
include '../config/database.php';

$sql = "SELECT * from additional_services";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {

    $id = $_POST['delete_id'];

    if (!is_numeric($id)) {
        die("ID tidak valid");
    }

    $stmt = $conn->prepare("DELETE FROM additional_services WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus'); window.location='';</script>";
    } else {
        echo "Gagal hapus: " . $stmt->error;
    }

    $stmt->close();
}

ob_start();
?>

<div class="header-content flex items-center justify-between mb-4 align-middle">
    <h1 class="text-2xl font-bold text-gray-800">Additional Services</h1>
    <a href="create-service.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Tambah Service</a>
</div>
<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 text-left">
    <table class="w-full border-collapse">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">No</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Service Name</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Duration Type</th>
                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Harga</th>
                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php

            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='hover:bg-gray-50 transition-colors'>";
                    echo "<td class='px-6 py-4 text-center text-sm text-gray-600'>" . $no++ . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['service_name']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>" . htmlspecialchars($row['duration_type']) . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600'>Rp" . number_format($row['service_price'], 0, ',', '.') . "</td>";
                    echo "<td class='px-6 py-4 text-sm text-gray-600 flex gap-2 justify-center'>";
                    echo "<a href='edit-service.php?id=" . htmlspecialchars($row['id']) . "' class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm w-16 text-center'>Edit</a>";
                    echo "<button onclick=\"openModal(" . $row['id'] . ")\" class=\"bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm\">Hapus</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='py-20 text-center text-gray-400 font-medium italic'>Tidak ada data penyewa yang ditemukan.</td></tr>";
            }
            ?>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-80">
        <h2 class="text-lg font-semibold mb-4">Yakin ingin menghapus?</h2>

        <form method="POST">
            <input type="hidden" name="delete_id" id="delete_id">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-3 py-1 bg-gray-300 rounded">
                    Batal
                </button>
                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('delete_id').value = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

<?php
$content = ob_get_clean();
$serviceactive = "active";
include 'layouts/app.php';
?>

<?php
$conn->close();
?>