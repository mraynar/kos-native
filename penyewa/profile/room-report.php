<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'submit_report') {
    $user_id = $_SESSION['user_id'];
    $booking_id = $_POST['booking_id'];

    $get_room_info = mysqli_query($conn, "SELECT r.room_number FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = '$booking_id'");
    $room_info = mysqli_fetch_assoc($get_room_info);
    $location = "Kamar " . $room_info['room_number'];

    $issue_name = mysqli_real_escape_string($conn, $_POST['issue_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $photo_name = null;
    if (isset($_FILES['issue_photo']) && $_FILES['issue_photo']['error'] === 0) {

        $root_dir = $_SERVER['DOCUMENT_ROOT'] . "/kos-native/";
        $target_dir = $root_dir . "assets/img/reports/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['issue_photo']['name'], PATHINFO_EXTENSION);
        $photo_name = "ISSUE_" . time() . "_" . $user_id . "." . $ext;

        $destination = $target_dir . $photo_name;

        if (!move_uploaded_file($_FILES['issue_photo']['tmp_name'], $destination)) {
            echo "<script>alert('Gagal memindahkan file ke folder tujuan. Periksa izin akses folder.');</script>";
        }
    }

    $query = "INSERT INTO maintenance_requests (user_id, booking_id, issue_name, description, photo, status, location, created_at) 
              VALUES ('$user_id', '$booking_id', '$issue_name', '$description', '$photo_name', 'pending', '$location', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Laporan berhasil dikirim!'); window.location='index.php?tab=report';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

$today = date('Y-m-d');
$active_bookings_query = mysqli_query($conn, "
    SELECT b.id, b.check_in, b.check_out, r.room_number 
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id
    WHERE b.user_id = '$user_id' 
    AND b.status = 'paid' 
    AND '$today' BETWEEN b.check_in AND b.check_out
");

$all_active = [];
while ($row = mysqli_fetch_assoc($active_bookings_query)) {
    $all_active[] = $row;
}
?>

<div class="flex items-center gap-4 mb-10 text-left">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Lapor Permasalahan Kamar</h3>
</div>

<?php if (!empty($all_active)): ?>
    <form action="index.php?tab=report&action=submit_report" method="POST" enctype="multipart/form-data" class="space-y-10 text-left">

        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-info-circle text-primary text-xs"></i>
                <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Informasi Kamar Aktif</h4>
            </div>

            <div class="p-8 bg-slate-50 rounded-[40px] border border-slate-100 shadow-inner">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 text-left">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Nomor Kamar Anda</label>
                        <select name="booking_id" id="booking_selector" required
                            class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-primary outline-none focus:ring-4 focus:ring-primary/5 transition-all text-sm appearance-none cursor-pointer">
                            <?php foreach ($all_active as $booking): ?>
                                <option value="<?= $booking['id'] ?>"
                                    data-range="<?= date('d M Y', strtotime($booking['check_in'])) ?> - <?= date('d M Y', strtotime($booking['check_out'])) ?>">
                                    Kamar <?= $booking['room_number'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Masa Aktif Sewa</label>
                        <input type="text" id="masa_sewa_display" value="" disabled
                            class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <i class="fas fa-tools text-primary text-xs"></i>
                <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Detail Permasalahan</h4>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Subjek Permasalahan</label>
                    <input type="text" name="issue_name" required placeholder="Contoh: AC tidak dingin, Lampu kamar mandi mati"
                        class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all text-sm">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Deskripsi Kerusakan</label>
                    <textarea name="description" required placeholder="Mohon jelaskan kendala Anda agar teknisi kami dapat menyiapakan peralatan yang tepat..."
                        class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none h-32 transition-all text-sm"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Foto Bukti (Gunakan Kamera)</label>
                    <div class="relative group">
                        <input type="file" name="issue_photo" accept="image/*" capture="camera"
                            class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-500 focus:ring-4 focus:ring-primary/5 focus:border-primary outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-primary file:text-white hover:file:bg-slate-900 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <button type="submit" class="group flex items-center gap-3 px-12 py-5 bg-primary text-white font-black rounded-[28px] shadow-xl shadow-primary/20 hover:bg-slate-900 hover:scale-[1.02] active:scale-95 transition-all tracking-tight uppercase text-[11px]">
                <span>Kirim Laporan Sekarang</span>
                <i class="fas fa-paper-plane text-xs group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </form>

    <script>
        const selector = document.getElementById('booking_selector');
        const display = document.getElementById('masa_sewa_display');

        function updateMasaSewa() {
            const selectedOption = selector.options[selector.selectedIndex];
            const range = selectedOption.getAttribute('data-range');
            display.value = range;
        }

        selector.addEventListener('change', updateMasaSewa);
        window.addEventListener('DOMContentLoaded', updateMasaSewa); 
    </script>

<?php else: ?>
    <div class="flex flex-col items-center justify-center py-20 text-center space-y-6">
        <div class="w-24 h-24 bg-red-50 text-red-500 rounded-[32px] flex items-center justify-center text-3xl shadow-inner">
            <i class="fas fa-lock"></i>
        </div>
        <div class="space-y-2">
            <h4 class="text-xl font-black text-slate-800">Layanan Laporan Terkunci</h4>
            <p class="text-sm font-medium text-slate-500 max-w-sm mx-auto">Fitur ini hanya tersedia bagi penghuni yang sedang memiliki masa sewa aktif di Griya Asri Kos.</p>
        </div>
    </div>
<?php endif; ?>