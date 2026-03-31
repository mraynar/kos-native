<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';
require_once '../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: /kos-native/auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id_booking = $_GET['id'] ?? '';

if (empty($id_booking)) {
    die("ID Transaksi tidak ditemukan.");
}

$query = "SELECT bookings.*, rooms.room_number, room_types.name as type_name, 
          room_types.base_price_daily, users.full_name_ktp, users.email, users.phone 
          FROM bookings 
          JOIN rooms ON bookings.room_id = rooms.id 
          JOIN room_types ON rooms.room_type_id = room_types.id
          JOIN users ON bookings.user_id = users.id 
          WHERE bookings.id = '$id_booking' AND bookings.user_id = '$user_id' LIMIT 1";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data transaksi tidak ditemukan.");
}

$services_query = mysqli_query($conn, "SELECT bs.*, ads.service_name, ads.service_price, ads.duration_type 
                                       FROM booking_service bs
                                       JOIN additional_services ads ON bs.additional_service_id = ads.id
                                       WHERE bs.booking_id = '$id_booking'");
$services = [];
$total_additional_cost = 0;
while ($row = mysqli_fetch_assoc($services_query)) {
    $services[] = $row;
}

\Midtrans\Config::$serverKey = 'Mid-server-QWIUeWSf_M92Na-vnWXvLS5E';
\Midtrans\Config::$isProduction = false;
$payment_method = "MIDTRANS PAYMENT";
$payment_time = $data['updated_at'];

try {
    $status_midtrans = \Midtrans\Transaction::status($id_booking);
    $status_array = (array)$status_midtrans;
    $raw_method = $status_array['payment_type'] ?? 'Midtrans Payment';
    $payment_method = strtoupper(str_replace('_', ' ', $raw_method));
    $payment_time = $status_array['transaction_time'] ?? $data['updated_at'];
} catch (Exception $e) {
}

$d1 = new DateTime($data['check_in']);
$d2 = new DateTime($data['check_out']);
$total_days = $d1->diff($d2)->days ?: 1;

$total_service_calculated = 0;
foreach ($services as $s) {
    $qty_s = ($s['duration_type'] == 'Mingguan') ? floor($total_days / 7) : $total_days;
    if ($qty_s < 1) $qty_s = 1;
    $total_service_calculated += ($s['service_price'] * $qty_s);
}
$room_only_price = $data['total_price'] - $total_service_calculated;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota_GriyaAsri_<?= $data['id'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }

            @page {
                margin: 0;
                size: portrait;
            }

            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                padding: 2rem !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 p-4 md:p-8 antialiased" onload="setTimeout(() => { window.print(); }, 1000);">

    <div class="max-w-2xl mx-auto mb-6 no-print flex justify-between items-center">
        <a href="index.php?tab=history" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-primary transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat
        </a>
        <button onclick="window.print()" class="bg-slate-900 text-white px-8 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-black transition-all">
            <i class="fas fa-print mr-2"></i> Cetak Nota
        </button>
    </div>

    <div class="max-w-2xl mx-auto bg-white p-10 md:p-14 rounded-[50px] shadow-2xl border border-slate-100 receipt-card relative overflow-hidden">

        <div class="flex justify-between items-start mb-10 border-b-2 border-slate-50 pb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-slate-200">
                        <i class="fas fa-home"></i>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tighter">Griya Asri Kos.</h1>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-1">Electronic Payment Receipt</p>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-2xl mb-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">Lunas (Paid)</span>
                </div>
                <p class="text-[11px] font-bold text-slate-800">#<?= $data['id'] ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-10 mb-10 text-[11px]">
            <div class="space-y-4">
                <div>
                    <h6 class="font-black text-slate-400 uppercase tracking-widest mb-1.5">Informasi Penyewa</h6>
                    <p class="font-black text-slate-800 text-sm"><?= $data['full_name_ktp'] ?: 'Muhammad Raynar Hammam' ?></p>
                    <p class="font-bold text-slate-500 mt-0.5"><?= $data['email'] ?></p>
                </div>
                <div>
                    <h6 class="font-black text-slate-400 uppercase tracking-widest mb-1.5">Periode Sewa</h6>
                    <p class="font-bold text-slate-800"><?= date('d M Y', strtotime($data['check_in'])) ?> — <?= date('d M Y', strtotime($data['check_out'])) ?></p>
                    <p class="text-primary font-black uppercase mt-0.5"><?= $total_days ?> Hari Hunian</p>
                </div>
            </div>
            <div class="text-right space-y-4">
                <div>
                    <h6 class="font-black text-slate-400 uppercase tracking-widest mb-1.5">Detail Pembayaran</h6>
                    <p class="font-black text-slate-800 uppercase"><?= $payment_method ?></p>
                    <p class="font-bold text-slate-500 mt-0.5"><?= date('d F Y, H:i', strtotime($payment_time)) ?> WIB</p>
                </div>
                <div>
                    <h6 class="font-black text-slate-400 uppercase tracking-widest mb-1.5">Lokasi Hunian</h6>
                    <p class="font-bold text-slate-800">Gunung Anyar, Surabaya</p>
                    <p class="font-bold text-slate-500">Jawa Timur, Indonesia</p>
                </div>
            </div>
        </div>

        <div class="space-y-4 mb-10">
            <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 border-b pb-2 flex items-center gap-2">
                <i class="fas fa-list-ul text-primary"></i> Rincian Pesanan
            </h6>

            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-6 flex justify-between items-center group transition-all">
                <div class="text-left">
                    <p class="text-sm font-black text-slate-800 leading-none mb-2">Biaya Kamar No. <?= $data['room_number'] ?></p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi <?= $total_days ?> Hari | Tipe <?= $data['type_name'] ?></p>
                </div>
                <p class="text-md font-black text-slate-900 tracking-tight">Rp <?= number_format($room_only_price, 0, ',', '.') ?></p>
            </div>

            <?php foreach ($services as $s):
                $qty_item = ($s['duration_type'] == 'Mingguan') ? floor($total_days / 7) : $total_days;
                if ($qty_item < 1) $qty_item = 1;
                $unit = ($s['duration_type'] == 'Mingguan') ? 'MINGGU' : 'HARI';
                $sub_cost = $s['service_price'] * $qty_item;
            ?>
                <div class="bg-white border-2 border-slate-50 rounded-3xl p-6 flex justify-between items-center">
                    <div class="text-left">
                        <p class="text-sm font-bold text-slate-700 leading-none mb-2"><?= $s['service_name'] ?></p>
                        <p class="text-[9px] font-black text-primary uppercase tracking-tighter">
                            QTY: <?= $qty_item ?> <?= $unit ?> <span class="text-slate-200 mx-2">|</span> @ <?= number_format($s['service_price'], 0, ',', '.') ?>
                        </p>
                    </div>
                    <p class="text-sm font-black text-slate-700">Rp <?= number_format($sub_cost, 0, ',', '.') ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="bg-slate-900 rounded-[35px] p-8 text-white flex justify-between items-center shadow-2xl shadow-slate-200">
            <div class="text-left">
                <p class="text-[10px] font-black uppercase tracking-[0.4em] opacity-40 mb-2">Total Tagihan Lunas</p>
                <div class="flex items-center gap-2">
                    <span class="bg-emerald-500 text-[8px] font-black px-2 py-1 rounded-lg uppercase tracking-widest text-white shadow-lg">Secure Checkout</span>
                    <span class="text-[10px] font-bold text-slate-400">Inc. Service Tax</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-black tracking-tighter leading-none">Rp <?= number_format($data['total_price'], 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="mt-14 pt-8 border-t border-dashed border-slate-100 text-center">
            <p class="text-[11px] font-bold text-slate-400 mb-6 max-w-xs mx-auto leading-relaxed uppercase tracking-wider">
                Terima kasih telah mempercayai hunian Anda di <span class="text-slate-900 font-black">Griya Asri Kos Surabaya</span>.
            </p>
            <div class="flex justify-center gap-8 mb-8">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_Midtrans.png" class="h-3 opacity-20 grayscale" alt="Midtrans">
                <div class="h-3 w-[1px] bg-slate-200"></div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Official Document &copy; 2026</p>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center hidden print:block">
        <p class="text-[8px] text-slate-300 font-bold uppercase tracking-[0.4em]">E-Receipt Generated on <?= date('d/m/Y H:i:s') ?> | Digital Evidence of Griya Asri Kos</p>
    </div>

</body>

</html>