<?php
include '../config/database.php';

$totalProperti = $conn->query("SELECT count(*) as total FROM rooms")->fetch_assoc()['total'];
$totalUser = $conn->query("SELECT count(*) as total FROM users")->fetch_assoc()['total'];
$totalPesanan = $conn->query("SELECT count(*) as total FROM bookings")->fetch_assoc()['total'];

$queryPendapatan = $conn->query("SELECT SUM(total_price) as total_pendapatan FROM bookings WHERE status = 'paid'");
$dataPendapatan = $queryPendapatan->fetch_assoc();
$totalPendapatan = $dataPendapatan['total_pendapatan'] ?? 0;

$queryPie = $conn->query("
    SELECT room_type_id, COUNT(*) as total
    FROM rooms
    GROUP BY room_type_id
");

$labels = [];
$series = [];

while ($row = $queryPie->fetch_assoc()) {
    $labels[] = $row['room_type_id'] == 1 ? 'Hemat' : ($row['room_type_id'] == 2 ? 'Santai' : ($row['room_type_id'] == 3 ? 'Nyaman' : 'Luas'));
    $series[] = (int)$row['total'];
}

$queryLine = $conn->query("
    SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(total_price) as total_income
FROM bookings
WHERE status = 'paid'
GROUP BY DATE_FORMAT(created_at, '%Y-%m')
ORDER BY month;
");

$categories = [];
$data = [];

while ($row = $queryLine->fetch_assoc()) {
    $categories[] = $row['month']; // Jan, Feb, dst
    $data[] = (int)$row['total_income'];
}

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
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Pemasukan</h3>
        <div class="h-auto bg-gray-100 rounded flex items-center justify-center">
            <div class="pt-6 px-2 pb-0">
                <div id="line-chart"></div>
            </div>
            <div class="px-2">
                <!-- <h3 class="text-lg font-semibold mb-3">Keterangan</h3>

                <ul class="space-y-2 text-sm text-gray-700">
                    <li>📈 Pemasukan tertinggi: Maret</li>
                    <li>📉 Penurunan terjadi di April</li>
                    <li>💰 Total transaksi hanya dari status <b>paid</b></li>
                </ul> -->
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Properti</h3>
        <div class="h-auto bg-gray-100 rounded flex items-center justify-center">
            <div class="py-6 mt-4 grid place-items-center px-2 overflow-hidden rounded-none bg-transparent bg-clip-border">
                <div id="pie-chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const pieChart = {
        series: <?= json_encode($series) ?>,
        chart: {
            type: "pie",
            width: 280,
            height: 280,
            toolbar: {
                show: false,
            },
        },
        title: {
            show: "",
        },
        labels: <?= json_encode($labels) ?>,
        dataLabels: {
            enabled: false,
        },
        colors: ["#020617", "#ff8f00", "#00897b", "#1e88e5", "#d81b60"],
        legend: {
            show: false,
        },
    };

    const lineChart = {
        series: [{
            name: "Pemasukan",
            data: <?= json_encode($data) ?>,
        }, ],
        chart: {
            type: "line",
            height: 310,
            width: 500,
            toolbar: {
                show: false,
            },
        },
        title: {
            show: "",
        },
        dataLabels: {
            enabled: false,
        },
        colors: ["#020617"],
        stroke: {
            lineCap: "round",
            curve: "smooth",
        },
        markers: {
            size: 0,
        },
        xaxis: {
            axisTicks: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            labels: {
                style: {
                    colors: "#616161",
                    fontSize: "12px",
                    fontFamily: "inherit",
                    fontWeight: 400,
                },
            },
            categories: <?= json_encode($categories) ?>,
        },
        yaxis: {
            labels: {
                style: {
                    colors: "#616161",
                    fontSize: "12px",
                    fontFamily: "inherit",
                    fontWeight: 400,
                },
                formatter: function(val) {
                    return 'Rp' + val.toLocaleString('id-ID');
                },
            },
        },
        grid: {
            show: true,
            borderColor: "#dddddd",
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: true,
                },
            },
            padding: {
                top: 5,
                right: 20,
            },
        },
        fill: {
            opacity: 0.8,
        },
        tooltip: {
            theme: "dark",
            y: {
                formatter: function(val) {
                    return 'Rp' + val.toLocaleString('id-ID');
                }
            },
        },
    };

    const pieChartInstance = new ApexCharts(document.querySelector("#pie-chart"), pieChart),
        lineChartInstance = new ApexCharts(document.querySelector("#line-chart"), lineChart);

    pieChartInstance.render();
    lineChartInstance.render();
</script>

<?php
$content = ob_get_clean();
$indexactive = "active";
include 'layouts/app.php';
?>