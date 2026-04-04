<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100" onload="startTime()">
    <style>
        .active {
            background-color: #2563eb;
            border-radius: 0.5rem;
        }
    </style>
    <div class="flex h-screen">
        <div class="w-64 bg-gray-900 text-white p-6">
            <a href="index.php" class="flex justify-between mb-8 items-center mx-2">
                <i class="fas fa-home text-white text-xl"></i>
                <h1 class="text-xl font-bold">Griya Asri Admin</h1>
            </a>
            <nav class="space-y-4">
                <a href="index.php" class="<?= $indexactive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="list-properti.php" class="<?= $propertiactive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fas fa-home"></i>
                    <span>Properti</span>
                </a>
                <a href="list-service.php" class="<?= $serviceactive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>Services</span>
                </a>
                <a href="list-user.php" class="<?= $useractive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
                <a href="pegawai.php" class="<?= $pegawaiactive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <span>Pegawai</span>
                </a>
                <a href="list-pesanan.php" class="<?= $pesananactive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fas fa-file-invoice"></i>
                    <span>Pesanan</span>
                </a>
                <a href="assign-task.php" class="<?= $taskactive ?> flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Assign Task</span>
                </a>
                <!-- <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-800 rounded-lg transition">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a> -->
            </nav>
        </div>

        <div class="flex-1 flex flex-col">
            <div class="bg-white shadow p-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                <div class="text-2xl font-bold text-gray-800" id="jam"></div>
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
            if (i < 10) {
                i = "0" + i
            };
            return i;
        }
    </script>
</body>

</html>