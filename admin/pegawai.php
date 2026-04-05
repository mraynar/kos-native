<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$sql = "SELECT * FROM users WHERE role = 'pegawai' ORDER BY nickname ASC";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];

    if (!is_numeric($id)) {
        die("ID tidak valid");
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'pegawai'");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Akun pegawai berhasil dihapus'); window.location='pegawai.php';</script>";
    } else {
        echo "Gagal hapus: " . $stmt->error;
    }
    $stmt->close();
}

ob_start();
?>

<div class="">
    <div class="header-content flex items-center justify-between mb-4 align-middle">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Akun Pegawai</h1>
        <a href="create-pegawai.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow-sm transition-all">Tambah Pegawai</a>
    </div>

    <table class="w-full border-collapse border border-gray-300 shadow-sm">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="border border-gray-300 px-4 py-3 text-center text-sm uppercase tracking-wider">No</th>
                <th class="border border-gray-300 px-4 py-3 text-left text-sm uppercase tracking-wider">Nickname</th>
                <th class="border border-gray-300 px-4 py-3 text-left text-sm uppercase tracking-wider">Nama Lengkap</th>
                <th class="border border-gray-300 px-4 py-3 text-left text-sm uppercase tracking-wider">Email Login</th>
                <th class="border border-gray-300 px-4 py-3 text-left text-sm uppercase tracking-wider">WhatsApp</th>
                <th class="border border-gray-300 px-4 py-3 text-center text-sm uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <?php
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='hover:bg-gray-50 border-b border-gray-300 transition-colors'>";
                    echo "<td class='border border-gray-300 px-4 py-3 text-center text-gray-600'>" . $no++ . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-3 font-bold text-slate-800'>" . htmlspecialchars($row['nickname']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-3 text-gray-700'>" . htmlspecialchars($row['name'] ?? '-') . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-3 text-blue-600 font-medium'>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-3 text-gray-700'>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td class='border border-gray-300 px-4 py-3 flex gap-2 justify-center items-center'>";
                    echo "<a href='edit-pegawai.php?id=" . $row['id'] . "' class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-bold w-16 text-center transition-all'>Edit</a>";
                    echo "<button onclick=\"openModal(" . $row['id'] . ")\" class=\"bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-bold transition-all\">Hapus</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='py-10 text-center text-gray-400 font-medium italic'>Belum ada data pegawai yang terdaftar</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black/60 hidden justify-center items-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-2xl w-full max-w-xs shadow-2xl border border-gray-100">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-2xl"></i>
            </div>
            <h2 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Hapus Akun?</h2>
            <p class="text-xs text-gray-400 font-bold uppercase mt-1">Pegawai ini tidak akan bisa login lagi.</p>
        </div>

        <form method="POST">
            <input type="hidden" name="delete_id" id="delete_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-black uppercase text-[10px] tracking-widest">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-red-200">
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
$pegawaiactive = "active";
include 'layouts/app.php';
$conn->close();
?>