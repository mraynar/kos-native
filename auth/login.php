<?php
require_once '../config/database.php';
session_start();

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' OR phone = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nickname'] = $user['nickname'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../" . $user['role'] . "/dashboard.php");
        exit();
    } else {
        $error = "Nomor HP/Email atau kata sandi salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Griya Asri Kos</title>
    <link rel="stylesheet" href="/sewa-kos/assets/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center">
        <div class="flex min-h-screen bg-slate-100">
            <div class="hidden lg:flex lg:w-3/5 bg-primary relative overflow-hidden items-center justify-center">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent z-10"></div>
                <img src="../assets/img/cover_web/kos3.jpg" class="absolute inset-0 w-full h-full object-cover object-center scale-105 hover:scale-100 transition-transform duration-700" alt="Interior Kos">
                <div class="relative z-20 text-white p-16 max-w-xl">
                    <h1 class="text-6xl font-black leading-tight mb-6 drop-shadow-2xl">Griya Asri Kos.</h1>
                    <p class="text-xl text-shadow-slate-700 font-medium text-blue-100 leading-relaxed text-justify drop-shadow-lg">
                        "Kenyamanan hunian adalah awal dari setiap prestasi besar mahasiswa. Kami hadir untuk memastikan fokus belajar Anda tidak terganggu oleh urusan tempat tinggal."
                    </p>
                    <div class="mt-12 flex items-center gap-5 bg-white/10 backdrop-blur-2xl p-5 rounded-[28px] border border-white/20 shadow-2xl w-fit group hover:bg-white/20 transition-all duration-500">
                        <div class="w-14 h-14 bg-gradient-to-br from-slate-800 to-primary rounded-2xl flex items-center justify-center text-2xl shadow-lg border border-white/10">
                            <i class="fas fa-shield-alt text-white/90 drop-shadow-[0_0_8px_rgba(37,99,235,0.3)]"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-black text-white text-2xl tracking-tighter">4.9</p>
                                <div class="flex text-lg text-yellow-500/75 gap-0.5 ms-1">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-blue-100/70 font-bold tracking-[0.2em] uppercase mt-0.5">Trusted by Many Students</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-gray-50/50">
                <div class="max-w-md w-full bg-white p-8 md:p-10 rounded-4xl border border-gray-300 shadow-2xl shadow-slate-200/80">
                    <div class="mb-8 text-center">
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-2">Masuk</h2>
                        <p class="text-sm text-slate-500 font-medium">Belum punya akun? <a href="register.php" class="text-primary font-bold hover:underline ms-0.5">Daftar Sekarang</a></p>
                    </div>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-600 rounded-xl text-xs font-bold shadow-sm">Pendaftaran berhasil! Silakan masuk.</div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold shadow-sm"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-500 ml-1">Nomor HP atau Email</label>
                            <input type="text" name="email" required class="w-full px-4 py-3.5 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm placeholder:text-gray-400" placeholder="example@gmail.com">
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center px-1">
                                <label class="block text-xs font-bold text-slate-500">Kata Sandi</label>
                                <a href="#" class="text-xs font-bold text-primary hover:underline">Lupa kata sandi?</a>
                            </div>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" required class="w-full pl-4 pr-12 py-3.5 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm" placeholder="Masukkan kata sandi">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary transition-colors">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-base shadow-lg shadow-blue-100 hover:bg-primary-dark transition-all active:scale-[0.98] mt-2">Masuk</button>

                        <div class="pt-6">
                            <div class="relative flex items-center mb-6">
                                <div class="flex-grow border-t border-gray-100"></div>
                                <span class="flex-shrink mx-4 text-gray-300 text-[10px] font-bold uppercase tracking-widest">
                                    atau <?= (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'masuk' : 'daftar' ?> dengan
                                </span>
                                <div class="flex-grow border-t border-gray-100"></div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <a href="#" class="flex items-center justify-center py-3 border border-gray-300/70 rounded-xl hover:bg-gray-50 hover:border-primary/70 transition-all group">
                                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 group-hover:scale-110 transition-transform" alt="Google">
                                </a>

                                <a href="#" class="flex items-center justify-center py-3 border border-gray-300/70 rounded-xl hover:bg-gray-50 hover:border-primary/70 transition-all text-[#1877F2] group">
                                    <i class="fab fa-facebook text-xl group-hover:scale-110 transition-transform"></i>
                                </a>

                                <a href="#" class="flex items-center justify-center py-3 border border-gray-300/70 rounded-xl hover:bg-gray-50 hover:border-primary/70 transition-all text-black group">
                                    <i class="fab fa-apple text-xl group-hover:scale-110 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>