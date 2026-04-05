<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pegawai') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $today = date('Y-m-d');

    $target_dir = "../assets/img/bukti_tugas/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = pathinfo($_FILES["bukti_foto"]["name"], PATHINFO_EXTENSION);
    $file_name = "LAPOR_" . $service_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $file_name;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["bukti_foto"]["tmp_name"], $target_file)) {
        $query_report = "INSERT INTO service_reports (booking_service_id, report_date, proof_photo, created_at) 
                         VALUES ('$service_id', '$today', '$file_name', NOW())";

        if (mysqli_query($conn, $query_report)) {
            echo "<script>alert('Laporan harian berhasil dikirim!'); window.location='tugas-layanan.php';</script>";
        } else {
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal mengunggah foto. Periksa izin folder!'); window.location='tugas-layanan.php';</script>";
    }
} else {
    header("Location: tugas-layanan.php");
}
