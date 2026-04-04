<?php
require_once '../config/database.php';
$title = "Dashboard Penyewa";

$today = date('Y-m-d');

$expired_query = mysqli_query($conn, "SELECT room_id FROM bookings WHERE status = 'paid' AND check_out < '$today'");

if (mysqli_num_rows($expired_query) > 0) {
    while ($expired = mysqli_fetch_assoc($expired_query)) {
        $r_id = $expired['room_id'];
        // Kembalikan kamar ke status available
        mysqli_query($conn, "UPDATE rooms SET status = 'available' WHERE id = '$r_id'");
    }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$view_all = (isset($_GET['view']) && $_GET['view'] == 'all');

$conditions = [];
if (!empty($search)) {
    $conditions[] = "(
        rooms.room_number LIKE '%$search%' OR 
        room_types.name LIKE '%$search%' OR 
        rooms.facilities LIKE '%$search%' OR 
        rooms.area_size LIKE '%$search%' OR 
        rooms.room_rules LIKE '%$search%'
    )";
}

if (!empty($category)) {
    $conditions[] = "rooms.room_type_id = '$category'";
}

$where_sql = "";
if (count($conditions) > 0) {
    $where_sql = " WHERE " . implode(' AND ', $conditions);
}

$limit_sql = $view_all ? "" : "LIMIT 8";

$rooms_query = "SELECT rooms.*, room_types.name as type_name, room_types.image as type_image 
                FROM rooms 
                JOIN room_types ON rooms.room_type_id = room_types.id 
                $where_sql
                $limit_sql";
$rooms_result = mysqli_query($conn, $rooms_query);

$total_rooms_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms JOIN room_types ON rooms.room_type_id = room_types.id $where_sql");
$total_data = mysqli_fetch_assoc($total_rooms_query);
$total_rooms_count = $total_data['total'];

ob_start();
?>

<section class="pt-26 pb-20 bg-slate-200/75">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-white p-8 md:p-14 lg:p-16 overflow-hidden rounded-4xl shadow-premium border border-gray-100 -mt-6">

            <div class="lg:grid lg:grid-cols-12 lg:gap-12 items-center relative z-10">
                <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-7 lg:text-left">
                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-md md:text-sm font-black tracking-[0.2em] uppercase bg-slate-100 text-primary mb-8 border border-slate-200/50">
                        <i class="fas fa-rocket mr-2 text-primary"></i> Hunian Eksklusif Mahasiswa Surabaya
                    </span>

                    <h1 class="text-6xl font-black text-primary sm:text-5xl md:text-6xl mb-8 leading-[1.05] tracking-tight">
                        Kamar Nyaman <br>
                        Mulai Prestasi <br>
                        Dari Sini.
                    </h1>

                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-12 max-w-xl">
                        Nikmati fasilitas lengkap dan suasana tenang di <span class="text-primary font-bold">Griya Asri Kos</span>.
                        Lokasi strategis hanya 5 menit dari kampus <span class="text-primary font-bold">UPN Veteran Jawa Timur</span>.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch lg:justify-start">
                        <a href="#daftar-kamar"
                            class="flex-1 sm:flex-none px-10 py-4.5 rounded-2xl bg-primary text-white font-extrabold text-base text-center flex items-center justify-center min-w-[240px] shadow-xl shadow-primary/20 hover:scale-105 hover:shadow-2xl  transition-all duration-300 active:scale-110">
                            Lihat Pilihan Kamar
                        </a>

                        <div class="flex-1 sm:flex-none flex items-center gap-4 bg-slate-200 p-4 rounded-2xl border border-slate-300 shadow-sm transition-all duration-300 cursor-default group hover:bg-slate-300 hover:border-primary hover:shadow-lg min-w-[220px]">
                            <div class="w-11 h-11 bg-white border-3 border-slate-200 rounded-xl flex items-center justify-center text-primary shadow-sm flex-shrink-0 group-hover:scale-110 hover:border-slate-300 transition-all duration-500">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>

                            <div class="text-left">
                                <p class="text-[11px] font-black text-slate-600 uppercase tracking-[0.2em] leading-none mb-1.5">Lokasi Kami</p>
                                <p class="text-[13px] font-extrabold text-primary leading-tight tracking-tight">Gunung Anyar, Surabaya</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 relative lg:mt-0 lg:col-span-5">
                    <div class="relative mx-auto w-full rounded-[40px] shadow-2xl overflow-hidden group border-8 border-white bg-white">
                        <img class="w-full h-115 object-cover aspect-[4/5] transform group-hover:scale-105 transition-transform duration-1000 ease-out"
                            src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&q=80&w=800"
                            alt="Griya Asri Kos">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-transparent to-transparent opacity-70"></div>

                        <div class="absolute bottom-8 left-8 text-white">
                            <span class="bg-primary/90 backdrop-blur-md text-white text-[9px] font-black px-3 py-1 rounded-lg tracking-widest uppercase mb-3 inline-block">TERFAVORIT</span>
                            <p class="font-black text-3xl tracking-tighter text-white">Griya Asri Kos</p>
                            <p class="text-sm font-medium text-slate-200">Hunian nyaman, tenang, & aman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="daftar-kamar" class="py-16 max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
        <div class="max-w-2xl text-left">
            <h2 class="text-3xl md:text-4xl font-black text-primary tracking-tighter mb-3">
                Rekomendasi Terbaik Untukmu.
            </h2>
            <p class="text-base md:text-lg text-slate-500 font-medium">
                Menampilkan <?= mysqli_num_rows($rooms_result) ?> dari <?= $total_rooms_count ?> kamar tersedia.
            </p>
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none px-5 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-primary shadow-sm hover:border-slate-300 hover:shadow-lg transition-all active:scale-95 text-sm flex items-center gap-2">
                <i class="fas fa-star text-yellow-500"></i> Rating
            </button>
            <button class="flex-1 md:flex-none px-5 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-primary shadow-sm hover:border-slate-300 hover:shadow-lg transition-all active:scale-95 text-sm flex items-center gap-2">
                <i class="fas fa-fire text-orange-500"></i> Terbaru
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-12">
        <div class="w-full lg:w-auto overflow-x-auto no-scrollbar pb-2">
            <div class="flex gap-3">
                <a href="dashboard.php#daftar-kamar"
                    class="<?= empty($category) ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white text-slate-500 border-2 border-slate-300/80' ?> px-7 py-3.5 rounded-2xl transition min-w-max text-sm font-bold flex items-center justify-center">
                    Semua
                </a>
                <?php
                $cat_res = mysqli_query($conn, "SELECT * FROM room_types");
                while ($cat = mysqli_fetch_assoc($cat_res)):
                    $active = ($category == $cat['id']) ? 'bg-primary text-white border-primary' : 'bg-white text-slate-500 border-slate-300/80';
                ?>
                    <a href="?category=<?= $cat['id'] ?>&search=<?= $search ?>#daftar-kamar"
                        class="<?= $active ?> border-2 px-7 py-3.5 rounded-2xl shadow-sm hover:border-primary hover:text-primary transition min-w-max text-sm font-bold flex items-center justify-center">
                        <?= $cat['name'] ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="w-full lg:w-[500px]">
            <form action="#daftar-kamar" method="GET" class="relative group">
                <?php if (!empty($category)): ?>
                    <input type="hidden" name="category" value="<?= $category ?>">
                <?php endif; ?>

                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none z-10">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-primary transition-colors duration-300 text-sm"></i>
                </div>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nomor, tipe, atau fasilitas (ex: wifi, ac)..."
                    class="w-full pl-12 pr-36 py-3.5 bg-white border border-slate-300/80 rounded-2xl shadow-sm focus:outline-none focus:ring-4 focus:ring-primary/6 focus:border-primary transition-all duration-300 font-medium text-slate-700 text-sm">
                <div class="absolute inset-y-1.5 right-1.5 flex items-center">
                    <button type="submit" class="h-full px-8 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-all">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        <?php
        if (mysqli_num_rows($rooms_result) > 0):
            while ($room = mysqli_fetch_assoc($rooms_result)):

                $is_available = ($room['status'] === 'available');
        ?>
                <div class="rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 flex flex-col group relative 
                <?= !$is_available ? 'bg-slate-100 opacity-80' : 'bg-white hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/50' ?>">

                    <?php if (!$is_available): ?>
                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] z-20 flex items-center justify-center p-4">
                            <div class="bg-white/90 px-4 py-2 rounded-xl shadow-xl border border-white">
                                <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest leading-none">
                                    <i class="fas fa-ban mr-1 text-red-500"></i> Tidak Tersedia
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="/kos-native/assets/img/room_types/<?= $room['type_image'] ?>" alt="Kamar"
                            class="w-full h-full object-cover transition-transform duration-500 <?= $is_available ? 'group-hover:scale-110' : 'grayscale' ?>">

                        <div class="absolute top-3 left-3 right-3 flex justify-between items-start z-10">
                            <span class="text-[10px] font-black px-2 py-1 bg-white/90 backdrop-blur text-primary rounded-lg uppercase"><?= $room['type_name'] ?></span>
                            <span class="text-[10px] font-black px-2 py-1 <?= ($room['gender_type'] == 'Putra') ? 'bg-primary' : 'bg-pink-500' ?> text-white rounded-lg uppercase"><?= $room['gender_type'] ?></span>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow text-left">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-slate-800">No. <?= $room['room_number'] ?></h3>
                            <span class="text-yellow-500 font-bold text-md flex items-center gap-1">
                                <i class="fas fa-star text-sm"></i> <?= $room['rating'] ?>
                            </span>
                        </div>
                        <div class="mt-2 mb-4">
                            <p class="text-[10px] font-black text-primary/60 uppercase tracking-widest mb-0.5">Harga Sewa</p>
                            <span class="text-primary font-extrabold text-xl">Rp <?= number_format($room['price']) ?></span>
                            <span class="text-slate-600 text-sm font-semibold italic">/bln</span>
                        </div>

                        <div class="mt-auto pt-2">
                            <?php if ($is_available): ?>
                                <a href="transactions/show.php?id=<?= $room['id'] ?>" class="block text-center bg-primary text-white hover:bg-primary-dark py-2.5 rounded-xl font-black text-sm transition-all shadow-lg shadow-blue-100/50">
                                    Lihat Detail
                                </a>
                            <?php else: ?>
                                <button disabled class="w-full bg-slate-200 text-slate-400 py-2.5 rounded-xl font-black text-sm cursor-not-allowed">
                                    Terisi / Dibooking
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        <?php
            endwhile;
        else:
            echo "<div class='col-span-full py-10 text-center text-slate-400 font-bold'>Kamar tidak ditemukan...</div>";
        endif;
        ?>
    </div>

    <div class="mt-16 text-center">
        <?php if (!isset($_GET['view']) && $total_rooms_count > 8): ?>
            <a href="dashboard.php?view=all#daftar-kamar" class="inline-flex items-center gap-2 px-12 py-4 bg-white border-2 border-primary text-primary font-black rounded-2xl hover:bg-primary hover:text-white transition-all shadow-xl shadow-primary/10">
                Tampilkan Semua Kamar <i class="fas fa-arrow-down"></i>
            </a>
        <?php elseif (isset($_GET['view']) && $_GET['view'] == 'all'): ?>
            <a href="dashboard.php#daftar-kamar" class="inline-flex items-center gap-2 px-12 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-200 transition-all">
                Tampilkan Lebih Sedikit <i class="fas fa-arrow-up"></i>
            </a>
        <?php endif; ?>
    </div>
</section>

<section id="about" class="pt-36 pb-36 bg-slate-200/75">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-24 items-center">

            <div class="mb-16 lg:mb-0">
                <h2 class="text-4xl md:text-5xl font-black text-primary leading-[1.1] tracking-tighter mb-8">
                    Griya Asri Kos <br>
                    <span class="not-italic text-primary text-3xl md:text-4xl">Hunian Strategis Mahasiswa UPN</span>
                </h2>

                <div class="space-y-6 text-slate-700 font-medium leading-relaxed text-justify text-lg">
                    <p>
                        Kami memahami bahwa lingkungan tenang adalah kunci sukses akademik. <span class="text-primary font-bold">Griya Asri Kos</span> menyediakan lebih dari sekadar kamar kami menyediakan ekosistem belajar yang memadai.
                    </p>
                    <p>
                        Berlokasi di Gunung Anyar, kami memberikan akses tercepat menuju kampus. Dengan manajemen digital profesional, setiap kebutuhan penghuni ditangani secara instan.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-2 gap-6">
                    <div class="px-6 py-5 bg-slate-300/75 rounded-3xl border shadow-lg border-slate-500 group hover:bg-primary transition-all duration-500 cursor-default">
                        <p class="text-primary font-black text-2xl group-hover:text-white transition-colors">5 Menit</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase tracking-widest mt-1 group-hover:text-slate-300">Jarak ke Kampus</p>
                    </div>
                    <div class="px-6 py-5 bg-slate-300/75 rounded-3xl border shadow-lg border-slate-500 group hover:bg-primary transition-all duration-500 cursor-default">
                        <p class="text-primary font-black text-2xl group-hover:text-white transition-colors">24 Jam</p>
                        <p class="text-[11px] font-black text-slate-600 uppercase tracking-widest mt-1 group-hover:text-slate-300">CCTV & Security</p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="aspect-[4/5] md:aspect-[16/11] rounded-[56px] overflow-hidden shadow-2xl border-8 border-white bg-white">
                    <img src="/kos-native/assets/img/about/about-students.jpg"
                        class="w-full h-full object-cover" alt="Suasana Griya Asri Kos">
                </div>

                <div class="absolute -bottom-8 -left-8 bg-green-100 border border-green-400/75 px-6 py-5 rounded-[32px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] hidden md:block z-20">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-white border border-green-300 text-emerald-500 rounded-2xl flex items-center justify-center text-3xl shadow-sm">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="font-black text-primary text-2xl tracking-tighter leading-none">Terverifikasi</p>
                            <p class="text-[10px] text-slate-600 font-bold uppercase tracking-[0.2em] mt-2">Security System 2026</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'layouts/app.php';
?>