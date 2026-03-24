<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['role']) && $_SESSION['role'] === 'penyewa';
$nickname = $is_logged_in ? ($_SESSION['nickname'] ?? 'Penyewa') : 'Tamu';

?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth scroll-pt-24">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Griya Asri Kos' ?></title>
    <link rel="stylesheet" href="/sewa-kos/assets/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-surface font-sans antialiased text-slate-900">

    <nav x-data="{ activeSection: 'home' }"
        @scroll.window="
        let sections = ['home', 'daftar-kamar', 'about'];
        sections.forEach(id => {
            let el = document.getElementById(id);
            if (el && window.scrollY >= el.offsetTop - 150) {
                activeSection = id;
            }
        });
        if (window.scrollY < 100) activeSection = 'home';
     "
        class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="/sewa-kos/penyewa/dashboard.php" class="text-2xl font-black text-primary tracking-tighter flex items-center gap-2">
                    <span class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center not-italic shadow-lg shadow-primary/20">
                        <i class="fas fa-home text-white text-xs"></i>
                    </span>
                    Griya Asri Kos
                </a>

                <div class="hidden md:flex items-center space-x-8">
                    <div class="flex items-center space-x-6">
                        <a href="/sewa-kos/penyewa/dashboard.php"
                            @click="activeSection = 'home'"
                            :class="activeSection === 'home' ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary'"
                            class="text-sm font-bold transition-all duration-300">
                            Beranda
                        </a>

                        <a href="/sewa-kos/penyewa/dashboard.php#daftar-kamar"
                            @click="activeSection = 'daftar-kamar'"
                            :class="activeSection === 'daftar-kamar' ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary'"
                            class="text-sm font-bold transition-all duration-300">
                            Kamar
                        </a>

                        <a href="/sewa-kos/penyewa/dashboard.php#about"
                            @click="activeSection = 'about'"
                            :class="activeSection === 'about' ? 'text-primary border-b-2 border-primary pb-1' : 'text-slate-500 hover:text-primary'"
                            class="text-sm font-bold transition-all duration-300">
                            Tentang
                        </a>
                    </div>

                    <div class="flex items-center gap-5 pl-4 border-l border-gray-200">
                        <?php if ($is_logged_in): ?>
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none">Mahasiswa</p>
                                <p class="text-sm font-bold text-primary"><?= htmlspecialchars($nickname) ?></p>
                            </div>
                            <a href="/sewa-kos/penyewa/profile/" class="w-11 h-11 bg-slate-100 text-primary rounded-2xl flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm">
                                <i class="fas fa-user-graduate text-lg"></i>
                            </a>
                        <?php else: ?>
                            <div class="flex items-center gap-2">
                                <a href="/sewa-kos/auth/login.php" class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-slate-600 hover:text-primary transition-all">
                                    Masuk
                                </a>
                                <a href="/sewa-kos/auth/register.php" class="px-7 py-3 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-primary transition-all shadow-xl shadow-slate-200">
                                    Daftar
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        <?= $content ?>
    </main>

    <footer class="bg-primary text-white pt-16 pb-8 rounded-t-4xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

                <div class="lg:col-span-1">
                    <h3 class="text-2xl font-black mb-6 italic">Griya Asri Kos.</h3>
                    <p class="text-blue-100 text-sm font-medium leading-relaxed mb-6">
                        Solusi hunian modern untuk mahasiswa kreatif. Kami percaya kenyamanan adalah awal dari setiap prestasi besar.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white/20 transition-all text-lg">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white/20 transition-all text-lg">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white/20 transition-all text-lg">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white/20 transition-all text-lg">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-black mb-6 uppercase tracking-widest text-blue-200">Navigasi</h4>
                    <ul class="space-y-4 font-bold text-sm">
                        <li><a href="/sewa-kos/penyewa/dashboard.php" class="hover:text-blue-200 transition-all">Beranda</a></li>
                        <li><a href="/sewa-kos/penyewa/dashboard.php#daftar-kamar" class="hover:text-blue-200 transition-all">Kamar</a></li>
                        <li><a href="/sewa-kos/penyewa/dashboard.php#about" class="hover:text-blue-200 transition-all">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-blue-200 transition-all">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-black mb-6 uppercase tracking-widest text-blue-200">Kebijakan</h4>
                    <ul class="space-y-4 font-bold text-sm">
                        <li><a href="#" class="hover:text-blue-200 transition-all">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-blue-200 transition-all">Peraturan Kos</a></li>
                        <li><a href="#" class="hover:text-blue-200 transition-all">Prosedur Booking</a></li>
                        <li><a href="#" class="hover:text-blue-200 transition-all">Bantuan Pelanggan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-black mb-6 uppercase tracking-widest text-blue-200">Hubungi Kami</h4>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-blue-200"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-blue-200 mb-1 tracking-widest">Email Support</p>
                                <p class="font-bold text-sm text-white">griyakos@gmail.com</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone-alt text-blue-200"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-blue-200 mb-1 tracking-widest">WhatsApp Admin</p>
                                <p class="font-bold text-sm text-white">+62 895 0239 0206</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-blue-200 font-bold text-[10px] uppercase tracking-[0.2em]">
                <p>&copy; 2026 Griya Asri Kos Surabaya. All rights reserved.</p>
                <p>Made for Mahasiswa Surabaya</p>
            </div>
        </div>
    </footer>
</body>

</html>