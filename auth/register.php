<?php
require_once '../config/database.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'penyewa';

    if ($password !== $confirm_password) {
        $error = "Konfirmasi kata sandi tidak cocok!";
    } elseif (strlen($password) < 8) {
        $error = "Kata sandi minimal harus 8 karakter!";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' OR phone = '$phone'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Email atau Nomor HP sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (name, nickname, email, phone, password, role) 
                      VALUES ('$nickname', '$nickname', '$email', '$phone', '$hashed_password', '$role')";

            if (mysqli_query($conn, $query)) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Terjadi kesalahan saat mendaftar: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Griya Asri Kos</title>
    <link rel="stylesheet" href="../assets/css/output.css">
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
                    <h1 class="text-[45px] font-black leading-tight mb-6 drop-shadow-2xl">Mulai Langkah Baru.</h1>
                    <p class="text-xl text-shadow-slate-700 font-medium text-blue-100 leading-relaxed text-justify drop-shadow-lg">
                        "Gabung bersama banyak mahasiswa lainnya dan temukan kenyamanan tempat belajar yang sesungguhnya. Kami hadir untuk memastikan masa depan Anda dimulai dari hunian yang tepat."
                    </p>
                    <div class="mt-12 flex items-center gap-5 bg-white/10 backdrop-blur-2xl p-5 rounded-[28px] border border-white/20 shadow-2xl w-fit group hover:bg-white/20 transition-all duration-500">
                        <div class="w-14 h-14 bg-gradient-to-br from-slate-800 to-primary rounded-2xl flex items-center justify-center text-3xl shadow-lg border border-white/10">
                            <i class="fas fa-shield-alt text-white/90 drop-shadow-[0_0_8px_rgba(37,99,235,0.3)]"></i>
                        </div>
                        <div>
                            <p class="font-black text-white text-xl tracking-tight">Pendaftaran Aman</p>
                            <p class="text-xs text-blue-100 font-bold tracking-[0.2em] uppercase mt-0.5">Verified System 2026</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-gray-50/50">
                <div class="max-w-md w-full bg-white p-8 md:p-10 rounded-4xl border border-gray-300 shadow-2xl shadow-slate-200/80">
                    <div class="mb-8 text-center">
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-2">Daftar Akun</h2>
                        <p class="text-sm text-slate-500 font-medium">Sudah punya akun? <a href="login.php" class="text-primary font-bold hover:underline ms-0.5">Masuk Sekarang</a></p>
                    </div>

                    <?php if ($error): ?>
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold shadow-sm"><?= $error ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-500 ml-1">Nama Panggilan</label>
                            <input type="text" name="nickname" required value="<?= isset($_POST['nickname']) ? htmlspecialchars($_POST['nickname']) : '' ?>" class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm placeholder:text-gray-400" placeholder="Masukkan nama panggilan anda">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-500 ml-1">Email</label>
                                <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm placeholder:text-gray-400" placeholder="example@gmail.com">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-500 ml-1">No. Telp</label>
                                <input type="tel" name="phone" required value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm placeholder:text-gray-400" placeholder="08130239...">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-500 ml-1">Kata Sandi</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" required class="w-full pl-4 pr-12 py-3 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm placeholder:text-gray-400" placeholder="Minimal 8 karakter">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary transition-colors">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-500 ml-1">Konfirmasi Kata Sandi</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="confirm_password" required class="w-full pl-4 pr-12 py-3 bg-white border border-gray-200 focus:border-primary rounded-xl transition-all font-medium text-slate-700 outline-none text-sm placeholder:text-gray-400" placeholder="Ulangi kata sandi">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary transition-colors">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-base shadow-lg shadow-blue-100 hover:bg-primary-dark transition-all active:scale-[0.98] mt-2">Buat Akun Sekarang</button>

                        <div class="pt-6">
                            <div class="relative flex items-center mb-6">
                                <div class="flex-grow border-t border-gray-100"></div>
                                <span class="flex-shrink mx-4 text-gray-300 text-[10px] font-bold uppercase tracking-widest">
                                    atau daftar dengan
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