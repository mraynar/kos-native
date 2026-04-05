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

<div class="">
    <div class="header-content flex items-center justify-between mb-4 align-middle">
        <h1 class="text-2xl font-bold text-gray-800">Additional Services</h1>
        <a href="create-service.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Tambah Service</a>
    </div>
    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-center">No</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Service Name</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Duration Type</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Harga</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='hover:bg-gray-100 border-b border-gray-300'>";
                    echo "<td class='border border-gray-300 px-4 py-2 text-center'>" . $no++ . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['service_name']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($row['duration_type']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2'>Rp" . number_format($row['service_price'], 0, ',', '.') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-2 flex gap-2 justify-center'>";
                    echo "<a href='edit-service.php?id=" . htmlspecialchars($row['id']) . "' class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm w-16 text-center'>Edit</a>";
                    echo "<button onclick=\"openModal(" . $row['id'] . ")\" class=\"bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm\">Hapus</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='py-3' style='text-align:center;'>Tidak ada data service</td></tr>";
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