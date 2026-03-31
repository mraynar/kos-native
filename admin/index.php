<?php
include '../config/database.php';

$totalProperti = $conn->query("SELECT count(*) as total FROM rooms")->fetch_assoc()['total'];
$totalUser = $conn->query("SELECT count(*) as total FROM users")->fetch_assoc()['total'];
$totalPesanan = $conn->query("SELECT count(*) as total FROM bookings")->fetch_assoc()['total'];

$queryPendapatan = $conn->query("SELECT SUM(total_price) as total_pendapatan FROM bookings WHERE status = 'paid'");
$dataPendapatan = $queryPendapatan->fetch_assoc();
$totalPendapatan = $dataPendapatan['total_pendapatan'] ?? 0;

ob_start();
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Total Properti</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalProperti ?> Kamar</p>
            </div>
            <i class="fas fa-home text-4xl text-blue-500 opacity-20"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Total Pengguna</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalUser ?> Pengguna</p>
            </div>
            <i class="fas fa-users text-4xl text-green-500 opacity-20"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalPesanan ?> Pesanan</p>
            </div>
            <i class="fas fa-shopping-cart text-4xl text-yellow-500 opacity-20"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-600 text-sm">Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900">
                    Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                </p>
            </div>
            <i class="fas fa-dollar-sign text-4xl text-red-500 opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Penjualan Bulanan</h3>
        <div class="h-64 bg-gray-100 rounded flex items-center justify-center">
            <p class="text-gray-500">Chart akan ditampilkan di sini</p>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Properti</h3>
        <div class="h-64 bg-gray-100 rounded flex items-center justify-center">
            <p class="text-gray-500">Chart akan ditampilkan di sini</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$indexactive = "active";
include 'layouts/app.php';
?>