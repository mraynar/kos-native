<?php
$host     = "localhost";
$username = "root";
$password = "root";
$database = "sewa_kos";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function getSetting($conn, $key)
{
    $stmt = $conn->prepare("SELECT value FROM settings WHERE `key`=?");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['value'] ?? '';
}
