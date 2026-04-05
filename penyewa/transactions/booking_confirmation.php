<?php
require_once '../../config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: /kos-native/auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php");
    exit();
}

$id_room = mysqli_real_escape_string($conn, $_POST['id_room'] ?? '');
$tgl_mulai = $_POST['tgl_mulai'] ?? '';
$tgl_selesai = $_POST['tgl_selesai'] ?? '';
$selected_services = $_POST['services'] ?? [];

function parseTanggal($tgl)
{
    $d = DateTime::createFromFormat('d/m/Y', $tgl);
    if ($d && $d->format('d/m/Y') === $tgl) return $d;
    return new DateTime($tgl);
}

try {
    $d1 = parseTanggal($tgl_mulai);
    $d2 = parseTanggal($tgl_selesai);
    $diff = $d1->diff($d2);
    $days = $diff->days > 0 ? $diff->days : 1;
} catch (Exception $e) {
    die("Format tanggal error!");
}

$query = "SELECT rooms.*, room_types.name as type_name, room_types.image as type_image,
          room_types.base_price_daily, room_types.base_price_weekly, room_types.base_price_monthly
          FROM rooms JOIN room_types ON rooms.room_type_id = room_types.id 
          WHERE rooms.id = '$id_room' LIMIT 1";
$res_room = mysqli_query($conn, $query);
$room = mysqli_fetch_assoc($res_room);

$p_daily = (int)$room['base_price_daily'];
$p_weekly = (int)$room['base_price_weekly'];
$p_monthly = (int)$room['base_price_monthly'];

if ($days >= 30) {
    $harga_kamar = (floor($days / 30) * $p_monthly) + (($days % 30) * $p_daily);
} elseif ($days >= 7) {
    $harga_kamar = (floor($days / 7) * $p_weekly) + (($days % 7) * $p_daily);
} else {
    $harga_kamar = $days * $p_daily;
}

$total_service = 0;
$detail_services = [];
if (!empty($selected_services)) {
    $ids = implode(',', array_map('intval', $selected_services));
    $serv_query = mysqli_query($conn, "SELECT * FROM additional_services WHERE id IN ($ids)");
    while ($s = mysqli_fetch_assoc($serv_query)) {
        $price = (int)$s['service_price'];
        $is_weekly = (strpos(strtolower($s['duration_type']), 'minggu') !== false);
        $qty = $is_weekly ? floor($days / 7) : $days;
        $unit = $is_weekly ? "Minggu" : "Hari";
        $cost = $price * $qty;
        $total_service += $cost;

        $detail_services[] = [
            'name' => $s['service_name'],
            'cost' => $cost,
            'qty' => $qty,
            'unit' => $unit,
            'price_unit' => $price
        ];
    }
}

$grand_total = $harga_kamar + $total_service;
$title = "Konfirmasi Pesanan - Griya Asri Kos";
ob_start();
?>

<div class="pt-6 pb-16 px-4 bg-slate-100 min-h-screen">
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center justify-center mb-10 gap-3">
            <div class="flex items-center gap-2 opacity-50">
                <span class="w-7 h-7 rounded-full bg-slate-500 text-white flex items-center justify-center text-[10px] font-black shadow-sm">1</span>
                <span class="text-[10px] font-black text-slate-500 uppercase italic">Pilih</span>
            </div>
            <div class="w-8 h-[1px] bg-slate-300"></div>

            <div class="flex items-center gap-2">
                <span class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center text-xs font-black shadow-lg shadow-primary/20 scale-110 ring-4 ring-primary/10">2</span>
                <span class="text-xs font-black text-primary uppercase italic underline underline-offset-4 decoration-2">Konfirmasi</span>
            </div>
            <div class="w-8 h-[1px] bg-slate-300"></div>

            <div class="flex items-center gap-2 opacity-30">
                <span class="w-7 h-7 rounded-full bg-slate-400 text-white flex items-center justify-center text-[10px] font-black shadow-sm">3</span>
                <span class="text-[10px] font-black text-slate-400 uppercase italic">Pembayaran</span>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-2xl shadow-slate-200 border border-white overflow-hidden">

            <div class="relative h-[250px] overflow-hidden group">
                <img src="/kos-native/assets/img/room_types/<?= $room['type_image'] ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-8">
                    <span class="bg-primary text-white px-4 py-1.5 rounded-xl text-sm font-black uppercase tracking-widest shadow-lg">
                        <?= $room['type_name'] ?>
                    </span>
                </div>
            </div>

            <div class="px-8 py-8 space-y-8">

                <div class="bg-slate-50 border border-slate-200 rounded-[30px] p-6 shadow-inner">
                    <div class="flex flex-row items-center justify-between text-center">
                        <div class="flex-1 border-r border-slate-200">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Mulai</p>
                            <p class="text-sm font-black text-slate-800 tracking-tight"><?= $d1->format('d M Y') ?></p>
                        </div>
                        <div class="px-4">
                            <span class="bg-slate-800 text-white px-4 py-2 rounded-2xl text-xs font-black shadow-lg">
                                <?= $days ?> Hari
                            </span>
                        </div>
                        <div class="flex-1 border-l border-slate-200">
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Selesai</p>
                            <p class="text-sm font-black text-slate-800 tracking-tight"><?= $d2->format('d M Y') ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-5 bg-primary rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-800">Rincian Pesanan</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center bg-slate-100/75 p-5 rounded-3xl border border-slate-300">
                            <div>
                                <p class="text-sm font-black text-slate-800">Biaya Kamar No. <?= $room['room_number'] ?></p>
                                <p class="text-[11px] text-slate-400 font-bold uppercase">Durasi <?= $days ?> Hari</p>
                            </div>
                            <span class="text-md font-black text-slate-900">Rp <?= number_format($harga_kamar) ?></span>
                        </div>

                        <?php foreach ($detail_services as $s): ?>
                            <div class="flex justify-between items-center bg-white p-5 rounded-3xl border border-slate-100 border-l-4 border-l-primary">
                                <div>
                                    <p class="text-sm font-bold text-slate-700"><?= $s['name'] ?></p>
                                    <p class="text-[10px] text-primary font-black uppercase tracking-tighter">
                                        Qty: <?= $s['qty'] ?> <?= $s['unit'] ?> <span class="text-slate-300 mx-1">|</span> @ <?= number_format($s['price_unit']) ?>
                                    </p>
                                </div>
                                <span class="text-sm font-black text-slate-700">Rp <?= number_format($s['cost']) ?></span>
                            </div>
                        <?php endforeach; ?>

                        <div class="pt-6 mt-4 border-t-2 border-dashed border-slate-200 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Tagihan</p>
                                <p class="text-3xl font-black text-primary tracking-tighter leading-none">Rp <?= number_format($grand_total) ?></p>
                            </div>
                            <div class="hidden md:flex flex-col items-end">
                                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black px-4 py-2 rounded-xl uppercase border border-emerald-100">
                                    <i class="fas fa-shield-check"></i> Secure Checkout
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="payment.php" method="POST" class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="service_details" value='<?= json_encode($detail_services) ?>'>
                    
                    <input type="hidden" name="id_room" value="<?= $id_room ?>">
                    <input type="hidden" name="total_price" value="<?= $grand_total ?>">
                    <input type="hidden" name="start_date" value="<?= $d1->format('d M Y') ?>">
                    <input type="hidden" name="end_date" value="<?= $d2->format('d M Y') ?>">
                    <input type="hidden" name="days" value="<?= $days ?>">

                    <a href="javascript:history.back()" class="py-5 bg-slate-100 text-slate-500 rounded-[28px] font-black text-center hover:bg-slate-200 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="py-5 bg-primary text-white rounded-[28px] font-black shadow-2xl hover:bg-slate-900 transition-all active:scale-95 group">
                        Lanjut ke Pembayaran <i class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

            </div>
        </div>

        <p class="text-center mt-8 text-slate-400 font-bold text-[11px] uppercase tracking-widest">
            Griya Asri Kos &copy; 2026 Surabaya
        </p>

    </div>
</div>

<?php
$content = ob_get_clean();
include '../layouts/app.php';
?>