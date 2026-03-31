<?php
require_once '../../config/database.php';
require_once '../../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penyewa') {
    header("Location: /kos-native/auth/login.php");
    exit();
}

// OPSIONAL BIAR STATUS PENDING = SUCCESS DI LOCALHOST
\Midtrans\Config::$serverKey = 'Mid-server-QWIUeWSf_M92Na-vnWXvLS5E';
\Midtrans\Config::$isProduction = false;

if (isset($_GET['tab']) && $_GET['tab'] === 'history') {
    $user_id = $_SESSION['user_id'];
    
    // Ambil semua booking yang statusnya masih pending milik user
    $check_pending = mysqli_query($conn, "SELECT id FROM bookings WHERE user_id = '$user_id' AND status = 'pending'");
    
    while ($booking = mysqli_fetch_assoc($check_pending)) {
        $order_id = $booking['id'];

        try {
            $status_midtrans = \Midtrans\Transaction::status($order_id);

            $status_array = (array)$status_midtrans;
            $response_status = $status_array['transaction_status'] ?? '';

            if ($response_status == 'settlement' || $response_status == 'capture') {
                mysqli_query($conn, "UPDATE bookings SET status = 'paid' WHERE id = '$order_id'");
                mysqli_query($conn, "UPDATE rooms SET status = 'occupied' WHERE id = (SELECT room_id FROM bookings WHERE id = '$order_id')");
            } else if ($response_status == 'expire') {
                mysqli_query($conn, "UPDATE bookings SET status = 'expired' WHERE id = '$order_id'");
            } else if ($response_status == 'cancel' || $response_status == 'deny') {
                mysqli_query($conn, "UPDATE bookings SET status = 'canceled' WHERE id = '$order_id'");
            }
        } catch (Exception $e) {
            continue;
        }
    }
}
// OPSIONAL BIAR STATUS PENDING = SUCCESS DI LOCALHOST

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'verify') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name_ktp']);
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Simulasi Nama File
    $ktp_name = "ktp_" . $user_id . ".jpg";
    $selfie_name = "selfie_" . $user_id . ".jpg";

    $update_query = "UPDATE users SET 
                        full_name_ktp = '$full_name',
                        birth_date = '$birth_date',
                        gender = '$gender',
                        address = '$address',
                        ktp_photo = '$ktp_name',
                        selfie_photo = '$selfie_name',
                        is_verified = 'verified' 
                      WHERE id = '$user_id'";

    if (mysqli_query($conn, $update_query)) {

        if (isset($_SESSION['redirect_after_verify'])) {
            $target = $_SESSION['redirect_after_verify'];
            unset($_SESSION['redirect_after_verify']);
            header("Location: " . $target);
        } else {
            header("Location: index.php?tab=verification&status=success");
        }
        exit();
    }
}

$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);

$tab = $_GET['tab'] ?? 'edit';
$title = "My Profile | Griya Asri Kos";

ob_start();
?>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-6 flex items-center gap-4">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div class="text-left">
                    <h2 class="text-lg font-black text-slate-800 leading-tight"><?= $user['nickname'] ?></h2>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1"><?= $user['role'] ?></p>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
                <nav class="p-2 space-y-1">
                    <a href="?tab=edit" class="flex items-center justify-between p-4 rounded-2xl transition-all group <?= $tab == 'edit' ? 'bg-primary text-white shadow-lg' : 'hover:bg-slate-50 text-slate-600' ?>">
                        <div class="flex items-center gap-4"><i class="fas fa-user-edit text-sm"></i><span class="text-sm font-bold">Edit Profile</span></div>
                        <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
                    </a>
                    <a href="?tab=history" class="flex items-center justify-between p-4 rounded-2xl transition-all group <?= $tab == 'history' ? 'bg-primary text-white shadow-lg' : 'hover:bg-slate-50 text-slate-600' ?>">
                        <div class="flex items-center gap-4"><i class="fas fa-history text-sm"></i><span class="text-sm font-bold">Rental History</span></div>
                        <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
                    </a>
                    <a href="?tab=verification" class="flex items-center justify-between p-4 rounded-2xl transition-all group <?= $tab == 'verification' ? 'bg-primary text-white shadow-lg' : 'hover:bg-slate-50 text-slate-600' ?>">
                        <div class="flex items-center gap-4"><i class="fas fa-id-card text-sm"></i><span class="text-sm font-bold">Verification</span></div>
                        <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
                    </a>
                    <a href="?tab=password" class="flex items-center justify-between p-4 rounded-2xl transition-all group <?= $tab == 'password' ? 'bg-primary text-white shadow-lg' : 'hover:bg-slate-50 text-slate-600' ?>">
                        <div class="flex items-center gap-4"><i class="fas fa-key text-sm"></i><span class="text-sm font-bold">Change Password</span></div>
                        <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
                    </a>
                    <div class="my-2 border-t border-slate-50"></div>
                    <a href="logout.php" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-red-50 text-red-500">
                        <i class="fas fa-sign-out-alt text-sm"></i><span class="text-sm font-black uppercase tracking-tighter">Sign Out</span>
                    </a>
                </nav>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-[40px] border border-slate-100 shadow-xl shadow-slate-200/50 p-8 md:p-12 min-h-[600px]">
                <?php
                switch ($tab) {
                    case 'history':
                        include 'rental-history.php';
                        break;
                    case 'verification':
                        include 'account-verification.php';
                        break;
                    case 'password':
                        include 'change-password.php';
                        break;
                    default:
                        include 'edit-profile.php';
                        break;
                }
                ?>
            </div>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
include '../layouts/app.php';
?>