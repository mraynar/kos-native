<?php
require_once '../../config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id_room = mysqli_real_escape_string($conn, $_GET['id']);

$query = "SELECT rooms.*, 
          room_types.name as type_name, 
          room_types.image as type_image, 
          room_types.description as type_desc,
          room_types.base_price_daily,
          room_types.base_price_weekly,
          room_types.base_price_monthly
          FROM rooms 
          JOIN room_types ON rooms.room_type_id = room_types.id 
          WHERE rooms.id = '$id_room' LIMIT 1";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Fatal Error Database: " . mysqli_error($conn));
}
$room = mysqli_fetch_assoc($result);

if (!$room) {
    header("Location: dashboard.php");
    exit();
}

$services_res = mysqli_query($conn, "SELECT * FROM additional_services");
$services = [];
if ($services_res) {
    while ($row = mysqli_fetch_assoc($services_res)) {
        $services[] = $row;
    }
}

$title = "Kamar " . $room['room_number'] . " | Griya Asri Kos";
ob_start();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="pt-8 pb-20 px-4 md:px-16 bg-slate-100 min-h-screen font-sans">

    <div class="mb-5 max-w-7xl mx-auto">
        <a href="../dashboard.php#daftar-kamar" class="text-primary hover:text-slate-950 flex items-center font-bold text-lg group transition-all w-fit">
            <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="hover:underline underline-offset-8 decoration-2">
                Kembali ke Daftar Kamar
            </span>
        </a>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 space-y-8">

            <div class="relative rounded-[40px] overflow-hidden shadow-2xl h-[450px] border-8 border-white bg-white group">
                <img src="/sewa-kos/assets/img/room_types/<?= $room['type_image'] ?>"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Interior">
                <div class="absolute top-6 left-6 flex gap-3">
                    <span class="bg-white/90 backdrop-blur-md px-5 py-2 rounded-2xl text-primary font-black text-[10px] uppercase tracking-[0.2em] shadow-xl">
                        <?= $room['type_name'] ?>
                    </span>
                    <span class="px-5 py-2 <?= $room['gender_type'] == 'Putra' ? 'bg-primary' : 'bg-pink-500' ?> text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl">
                        <i class="fas fa-user-group mr-2"></i><?= $room['gender_type'] ?>
                    </span>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Kamar <?= $room['room_number'] ?></h1>
                    <div class="flex items-center gap-2 bg-yellow-50 px-4 py-2 rounded-2xl border border-yellow-100">
                        <i class="fas fa-star text-yellow-500"></i>
                        <span class="font-black text-yellow-700"><?= $room['rating'] ?></span>
                    </div>
                </div>

                <p class="text-slate-600 font-semibold leading-relaxed text-md mb-10 italic">
                    "<?= $room['type_desc'] ?>"
                </p>

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-2 h-6 bg-blue-500 rounded-full"></div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Fasilitas Kamar</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4">
                    <?php
                    $facilities = explode(',', $room['facilities']);
                    foreach ($facilities as $item):
                    ?>
                        <div class="flex items-center gap-3 group">
                            <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center transition-all duration-300 group-hover:bg-primary">
                                <i class="fas fa-check text-[10px] text-primary group-hover:text-white"></i>
                            </div>
                            <span class="font-bold text-slate-600 text-[15px] tracking-tight group-hover:text-primary transition-colors">
                                <?= trim($item) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-2 h-6 bg-primary rounded-full"></div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Spesifikasi Kamar</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="flex items-center gap-4 p-5 bg-white rounded-3xl shadow-lg shadow-slate-100/50 border border-slate-200 group hover:border-primary transition-all">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center text-xl group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class="fas fa-vector-square"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Luas Kamar</p>
                            <p class="text-lg font-black text-slate-800 tracking-tight"><?= $room['area_size'] ?> M²</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-5 bg-white rounded-3xl shadow-lg shadow-slate-100/50 border border-slate-200 group hover:border-yellow-500 transition-all">
                        <div class="w-12 h-12 bg-yellow-50 text-yellow-500 rounded-xl flex items-center justify-center text-xl group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                            <i class="fas fa-bolt-lightning"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Sistem Listrik</p>
                            <p class="text-lg font-black text-slate-800 tracking-tight"><?= $room['is_electric_included'] ? 'Include' : 'Token' ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-5 bg-white rounded-3xl shadow-lg shadow-slate-100/50 border border-slate-200 group hover:border-blue-400 transition-all">
                        <div class="w-12 h-12 bg-blue-50 text-blue-400 rounded-xl flex items-center justify-center text-xl group-hover:bg-blue-400 group-hover:text-white transition-colors">
                            <i class="fas fa-hand-holding-droplet"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Biaya Air</p>
                            <p class="text-lg font-black text-slate-800 tracking-tight"><?= $room['is_water_included'] ? 'Gratis' : 'Bayar' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-2 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Informasi Harga</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Harian</p>
                        <p class="text-lg font-black text-primary">Rp <?= number_format($room['base_price_daily']) ?></p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Mingguan</p>
                        <p class="text-lg font-black text-primary">Rp <?= number_format($room['base_price_weekly']) ?></p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Bulanan</p>
                        <p class="text-lg font-black text-primary">Rp <?= number_format($room['base_price_monthly']) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-2 h-6 bg-red-500 rounded-full"></div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Peraturan Kamar</h3>
                </div>
                <div class="bg-red-50/50 border border-red-100 p-8 rounded-[30px] relative overflow-hidden">
                    <i class="fas fa-circle-exclamation absolute -right-4 -bottom-4 text-8xl text-red-500/5"></i>
                    <p class="text-slate-600 font-bold text-sm leading-relaxed whitespace-pre-line relative z-10">
                        <?= $room['room_rules'] ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="relative">
            <form action="booking_confirmation.php" method="POST" class="bg-white border border-gray-100 rounded-[40px] shadow-2xl p-8 sticky top-24">

                <input type="hidden" name="id_room" value="<?= $id_room ?>">

                <input type="hidden" name="total_price" id="inputTotal" value="0">

                <h3 class="text-xl font-black text-primary mb-6 tracking-tight">Informasi Sewa</h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Mulai</label>
                            <input type="text" name="tgl_mulai" id="tglMulai" class="w-full bg-slate-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary py-3.5 px-5 font-bold text-primary transition-all">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal Selesai</label>
                            <input type="text" name="tgl_selesai" id="tglSelesai" class="w-full bg-slate-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-primary/5 focus:border-primary py-3.5 px-5 font-bold text-primary transition-all">
                        </div>
                    </div>

                    <div class="flex justify-between items-center bg-blue-50 px-5 py-3 rounded-2xl border border-blue-100">
                        <span class="text-blue-800 font-bold text-sm">Durasi Sewa:</span>
                        <span id="textDurasi" class="text-primary font-black text-lg">0 Hari</span>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">Layanan Tambahan</h4>
                        <div class="space-y-1">
                            <?php foreach ($services as $service): ?>
                                <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 transition">
                                    <label class="flex items-center space-x-3 cursor-pointer w-full text-left">
                                        <input type="checkbox" name="services[]" value="<?= $service['id'] ?>" class="service-checkbox w-5 h-5 rounded text-primary"
                                            data-price="<?= $service['service_price'] ?>"
                                            data-type="<?= strtolower($service['duration_type']) ?>"
                                            onchange="hitungTotal()">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-700 leading-none mb-1"><?= $service['service_name'] ?></span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                                Rp <?= number_format($service['service_price']) ?> /<?= $service['duration_type'] ?>
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="pt-6 border-t-2 border-dashed border-slate-200">
                        <div class="flex justify-between items-center mb-6">
                            <div class="text-left">
                                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Estimasi Total</p>
                                <div class="flex items-baseline space-x-1">
                                    <span class="text-2xl font-black text-primary tracking-tighter">Rp</span>
                                    <span id="textTotal" class="text-4xl font-black text-primary tracking-tighter">0</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="bg-emerald-100 text-emerald-600 text-[9px] font-black px-3 py-2 rounded-full uppercase shadow-sm">
                                    <i class="fas fa-shield-alt"></i> Aman
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php
                        if (!isset($_SESSION['user_id'])):
                        ?>
                            <a href="../../auth/login.php" class="w-full bg-slate-900 hover:bg-black text-white font-black py-5 rounded-[24px] shadow-xl shadow-slate-200 transition-all active:scale-95 flex items-center justify-center gap-3 text-lg">
                                <i class="fas fa-sign-in-alt"></i> Login untuk Memesan
                            </a>

                            <?php
                        else:
                            $current_user_id = $_SESSION['user_id'];
                            $check_user_query = mysqli_query($conn, "SELECT is_verified FROM users WHERE id = '$current_user_id'");
                            $user_data = mysqli_fetch_assoc($check_user_query);

                            if ($user_data['is_verified'] !== 'verified'):
                            ?>
                                <a href="../profile/?tab=verification" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-[24px] shadow-xl shadow-amber-200 transition-all active:scale-95 flex items-center justify-center gap-3 text-lg">
                                    <i class="fas fa-id-card"></i> Verifikasi Akun untuk Memesan
                                </a>
                                <p class="text-[10px] text-amber-600 font-bold text-center bg-amber-50 p-3 rounded-xl border border-amber-100">
                                    <i class="fas fa-info-circle mr-1"></i> Sesuai peraturan keamanan Griya Asri Kos, Anda wajib melengkapi data identitas (KTP) sebelum melakukan pemesanan kamar.
                                </p>

                            <?php
                            else:
                            ?>
                                <button type="submit" id="btnPesan" disabled class="w-full bg-primary hover:bg-slate-900 text-white font-black py-5 rounded-[24px] shadow-xl shadow-primary/20 transition-all active:scale-95 flex items-center justify-center gap-3 text-lg disabled:opacity-50">
                                    <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                                </button>
                        <?php
                            endif; 
                        endif; 
                        ?>

                        <a href="https://wa.me/6289502390206" target="_blank" class="w-full flex items-center justify-center gap-3 border-2 border-green-500 text-green-600 font-black py-4 rounded-[24px] hover:bg-green-500 hover:text-white transition-all text-lg group">
                            <i class="fab fa-whatsapp text-2xl group-hover:scale-110 transition-transform"></i> Hubungi Owner
                        </a>
                </div>
        </div>
        </form>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    function hitungTotal() {
        const daily = <?= (int) $room['base_price_daily'] ?>;
        const weekly = <?= (int) $room['base_price_weekly'] ?>;
        const monthly = <?= (int) $room['base_price_monthly'] ?>;

        const tgl1 = document.getElementById('tglMulai').value;
        const tgl2 = document.getElementById('tglSelesai').value;
        const displayTotal = document.getElementById('textTotal');
        const inputTotal = document.getElementById('inputTotal');
        const displayDurasi = document.getElementById('textDurasi');
        const btn = document.getElementById('btnPesan');

        const isAvailable = <?= json_encode($room['status'] === 'available') ?>;

        if (tgl1 && tgl2) {
            const d1 = new Date(tgl1.split('/').reverse().join('-'));
            const d2 = new Date(tgl2.split('/').reverse().join('-'));
            const diffDays = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));

            if (diffDays > 0) {
                document.querySelectorAll('.service-checkbox').forEach(cb => {
                    const type = cb.getAttribute('data-type');
                    const parentDiv = cb.closest('.flex.items-center.justify-between');

                    if (type.includes('minggu') && diffDays < 7) {
                        cb.checked = false;
                        cb.disabled = true;
                        if (parentDiv) parentDiv.style.opacity = "0.4";
                    } else {
                        cb.disabled = false;
                        if (parentDiv) parentDiv.style.opacity = "1";
                    }
                });

                let hargaKamar = 0;
                if (diffDays >= 30) {
                    hargaKamar = (Math.floor(diffDays / 30) * monthly) + ((diffDays % 30) * daily);
                } else if (diffDays >= 7) {
                    hargaKamar = (Math.floor(diffDays / 7) * weekly) + ((diffDays % 7) * daily);
                } else {
                    hargaKamar = diffDays * daily;
                }

                let totalService = 0;
                document.querySelectorAll('.service-checkbox').forEach(cb => {
                    if (cb.checked && !cb.disabled) {
                        const price = Number(cb.getAttribute('data-price'));
                        const type = cb.getAttribute('data-type');
                        totalService += type.includes('minggu') ? (price * Math.floor(diffDays / 7)) : (price * diffDays);
                    }
                });

                const grandTotal = hargaKamar + totalService;
                displayTotal.innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
                inputTotal.value = grandTotal;
                displayDurasi.innerText = diffDays + ' Hari';

                if (isAvailable) btn.disabled = false;
            } else {
                displayTotal.innerText = "0";
                inputTotal.value = "0";
                displayDurasi.innerText = "0 Hari";
                btn.disabled = true;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const config = {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: hitungTotal
        };
        flatpickr("#tglMulai", {
            ...config,
            defaultDate: "today"
        });
        flatpickr("#tglSelesai", {
            ...config,
            defaultDate: new Date().getTime() + 86400000
        });
        setTimeout(hitungTotal, 500);
    });
</script>

<style>
    .flatpickr-calendar {
        width: 320px !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        border: none !important;
        padding: 10px;
    }

    .flatpickr-day.selected {
        background: #1E293B !important;
        border-color: #1E293B !important;
        border-radius: 12px !important;
    }

    .flatpickr-day {
        border-radius: 12px !important;
        font-weight: 700;
        height: 40px !important;
        line-height: 40px !important;
    }
</style>

<?php
$content = ob_get_clean();
include '../layouts/app.php';
?>