<?php
if (!function_exists('getSetting')) {
    function getSetting($conn, $key)
    {
        $query = mysqli_query($conn, "SELECT value FROM settings WHERE `key` = '$key' LIMIT 1");
        $result = mysqli_fetch_assoc($query);
        return $result['value'] ?? 'Griya Asri Kos';
    }
}

$pegawai_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$cek_absen = mysqli_query($conn, "SELECT * FROM absensi WHERE user_id = '$pegawai_id' AND tanggal = '$today'");
$sudah_absen = (mysqli_num_rows($cek_absen) > 0);

$user_query = mysqli_query($conn, "SELECT nickname FROM users WHERE id = '$pegawai_id'");
$user_data = mysqli_fetch_assoc($user_query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - <?= getSetting($conn, 'site_title'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .active {
            background-color: #2563eb;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-100" onload="startTime()">
    <div class="flex h-screen overflow-hidden">
        <div class="w-64 bg-gray-900 text-white p-6 flex flex-col">
            <div class="flex gap-2 mb-8 items-center mx-2">
                <i class="fas fa-user-shield text-blue-500 text-xl"></i>
                <h1 class="text-2xl font-black text-primary tracking-tighter"><?= getSetting($conn, 'site_title'); ?></h1>
            </div>
            
            <nav class="space-y-4 flex-1">
                <a href="dashboard.php" class="<?= $indexactive ?? '' ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition font-bold">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="absensi.php" class="<?= $absenactive ?? '' ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition font-bold">
                    <i class="fas fa-calendar-check w-5"></i>
                    <span>Absensi Harian</span>
                </a>
                <a href="tugas-layanan.php" class="<?= $tugasactive ?? '' ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition font-bold">
                    <i class="fas fa-concierge-bell w-5"></i>
                    <span>Tugas Layanan</span>
                </a>
                <a href="maintenance.php" class="<?= $maintactive ?? '' ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition font-bold">
                    <i class="fas fa-tools w-5"></i>
                    <span>Maintenance</span>
                </a>
                <a href="profile.php" class="<?= $profileactive ?? '' ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition font-bold">
                    <i class="fas fa-user w-5"></i>
                    <span>Profil</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col">
            <div class="bg-white shadow p-6 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase">
                        Halo, <?= $user_data['nickname'] ?? 'Pegawai'; ?>!
                    </h2>
                </div>
                <div class="text-2xl font-bold text-blue-600 font-mono tracking-tighter" id="jam"></div>
            </div>

            <div class="flex-1 overflow-auto p-6 bg-slate-200/75">
                <?= $content ?>
            </div>
        </div>
    </div>

    <script>
        function startTime() {
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('jam').innerHTML = h + ":" + m + ":" + s;
            setTimeout(startTime, 1000);
        }

        function checkTime(i) {
            if (i < 10) { i = "0" + i }; 
            return i;
        }
    </script>
</body>

</html>