<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$filter_type = $_GET['filter_type'] ?? 'month';

$totalProperti = $conn->query("SELECT count(*) as total FROM rooms")->fetch_assoc()['total'];
$totalUser = $conn->query("SELECT count(*) as total FROM users")->fetch_assoc()['total'];
$totalPesanan = $conn->query("SELECT count(*) as total FROM bookings")->fetch_assoc()['total'];
$totalPendapatan = $conn->query("SELECT SUM(total_price) as total FROM bookings WHERE status = 'paid'")->fetch_assoc()['total'] ?? 0;

$queryPie = $conn->query("
    SELECT 
        room_type_id, 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as sisa
    FROM rooms 
    GROUP BY room_type_id
");

$labels = [];
$series = [];
$sisaData = [];
while ($row = $queryPie->fetch_assoc()) {
    $labels[] = $row['room_type_id'] == 1 ? 'Hemat' : ($row['room_type_id'] == 2 ? 'Santai' : ($row['room_type_id'] == 3 ? 'Nyaman' : 'Luas'));
    $series[] = (int)$row['total'];
    $sisaData[] = (int)$row['sisa'];
}

if ($filter_type === 'year') {
    $dateFormat = '%b';
    $groupBy = "MONTH(created_at)";
    $whereClause = "YEAR(created_at) = YEAR(CURDATE())";
} elseif ($filter_type === 'month') {
    $dateFormat = 'Minggu %u';
    $groupBy = "WEEK(created_at, 1)";
    $whereClause = "MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
} else {
    $dateFormat = '%d %b';
    $groupBy = "DATE(created_at)";
    $whereClause = "YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
}

$queryLine = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '$dateFormat') as period, 
        SUM(total_price) as total_income
    FROM bookings 
    WHERE status = 'paid' AND $whereClause
    GROUP BY period
    ORDER BY period ASC
");

$categories = [];
$incomeData = [];
while ($row = $queryLine->fetch_assoc()) {
    $categories[] = $row['period'];
    $incomeData[] = (int)$row['total_income'];
}

ob_start();
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 border border-gray-50">
        <div class="flex justify-between items-center text-left">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Properti</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalProperti ?> Kamar</p>
            </div>
            <i class="fas fa-home text-3xl text-blue-500 opacity-20"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border border-gray-50">
        <div class="flex justify-between items-center text-left">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Pengguna</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalUser ?> Staf/User</p>
            </div>
            <i class="fas fa-users text-3xl text-green-500 opacity-20"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border border-gray-50">
        <div class="flex justify-between items-center text-left">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900"><?= $totalPesanan ?> Pesanan</p>
            </div>
            <i class="fas fa-shopping-cart text-3xl text-yellow-500 opacity-20"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border border-gray-50">
        <div class="flex justify-between items-center text-left">
            <div>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900">Rp<?= number_format($totalPendapatan, 0, ',', '.') ?></p>
            </div>
            <i class="fas fa-wallet text-3xl text-red-500 opacity-20"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Tren Pemasukan</h3>
            <form id="filterForm" method="GET" class="flex gap-1 bg-gray-100 p-1 rounded-lg border border-gray-200">
                <button type="submit" name="filter_type" value="week" class="px-3 py-1.5 text-[10px] font-bold uppercase rounded-md transition-all <?= $filter_type == 'week' ? 'bg-white shadow text-blue-600' : 'text-gray-400 hover:text-gray-600' ?>">Mingguan</button>
                <button type="submit" name="filter_type" value="month" class="px-3 py-1.5 text-[10px] font-bold uppercase rounded-md transition-all <?= $filter_type == 'month' ? 'bg-white shadow text-blue-600' : 'text-gray-400 hover:text-gray-600' ?>">Bulanan</button>
                <button type="submit" name="filter_type" value="year" class="px-3 py-1.5 text-[10px] font-bold uppercase rounded-md transition-all <?= $filter_type == 'year' ? 'bg-white shadow text-blue-600' : 'text-gray-400 hover:text-gray-600' ?>">Tahunan</button>
            </form>
        </div>
        <div class="flex-grow bg-gray-200/50 border border-gray-100 rounded-2xl flex items-center justify-center min-h-[400px]">
            <div id="line-chart" class="w-full px-2"></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 flex flex-col">
        <h3 class="text-lg font-bold text-gray-900 mb-6 text-left">Statistik Properti</h3>
        <div class="flex-grow bg-gray-200/50 border border-gray-100 rounded-2xl flex items-center justify-center min-h-[400px]">
            <div id="pie-chart"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const lineChart = new ApexCharts(document.querySelector("#line-chart"), {
        series: [{
            name: "Pendapatan",
            data: <?= json_encode($incomeData) ?>
        }],
        chart: {
            type: "line",
            height: 350,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },
        colors: ["#2563eb"],
        stroke: {
            curve: "smooth",
            width: 4
        },
        markers: {
            size: 4,
            colors: ["#2563eb"],
            strokeColors: "#fff",
            strokeWidth: 2
        },
        xaxis: {
            categories: <?= json_encode($categories) ?>,
            axisBorder: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: (v) => "Rp " + v.toLocaleString('id-ID')
            }
        },
        tooltip: {
            theme: "dark",
            y: {
                formatter: (v) => "Rp " + v.toLocaleString('id-ID')
            }
        },
        grid: {
            borderColor: "#f1f1f1",
            strokeDashArray: 4
        }
    });

    const sisaData = <?= json_encode($sisaData) ?>;
    const pieChart = new ApexCharts(document.querySelector("#pie-chart"), {
        series: <?= json_encode($series) ?>,
        labels: <?= json_encode($labels) ?>,
        chart: {
            type: "pie",
            width: 400
        },
        colors: ["#0f172a", "#f59e0b", "#10b981", "#3b82f6"],
        legend: {
            position: 'bottom',
            fontSize: '12px',
            fontWeight: 600
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '14px',
                fontWeight: 'bold'
            }
        },
        tooltip: {
            theme: "dark",
            custom: function({
                series,
                seriesIndex,
                dataPointIndex,
                w
            }) {
                return '<div class="px-3 py-2">' +
                    '<span class="font-bold">' + w.config.labels[seriesIndex] + '</span>' +
                    '<div class="text-xs mt-1">Total: ' + series[seriesIndex] + ' Kamar</div>' +
                    '<div class="text-xs text-green-400">Tersedia: ' + sisaData[seriesIndex] + ' Kamar</div>' +
                    '</div>';
            }
        }
    });

    lineChart.render();
    pieChart.render();
</script>

<?php
$content = ob_get_clean();
$indexactive = "active";
include 'layouts/app.php';
?>