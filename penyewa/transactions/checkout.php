<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../vendor/autoload.php';
require_once '../../config/database.php';

\Midtrans\Config::$serverKey = 'Mid-server-QWIUeWSf_M92Na-vnWXvLS5E';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !isset($data['id_room']) || !isset($data['check_in']) || !isset($data['check_out'])) {
        echo json_encode(['error' => 'Data input tidak lengkap atau tidak terbaca']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $id_room = mysqli_real_escape_string($conn, $data['id_room']);
    $total_price = (int)$data['total_price'];

    $service_details = $data['service_details'] ?? [];

    $check_in = date('Y-m-d', strtotime($data['check_in']));
    $check_out = date('Y-m-d', strtotime($data['check_out']));

    $order_id = 'KOS-' . time();
    $q_room = mysqli_query($conn, "SELECT room_number FROM rooms WHERE id = '$id_room'");
    $room_data = mysqli_fetch_assoc($q_room);
    $room_name = $room_data ? "Kamar " . $room_data['room_number'] : "Sewa Kamar";

    $insert_query = "INSERT INTO bookings (id, user_id, room_id, check_in, check_out, total_price, status, created_at, updated_at) 
                     VALUES ('$order_id', '$user_id', '$id_room', '$check_in', '$check_out', '$total_price', 'pending', NOW(), NOW())";

    if (mysqli_query($conn, $insert_query)) {

        if (!empty($service_details)) {
            foreach ($service_details as $s) {
                $s_name = mysqli_real_escape_string($conn, $s['name']);

                $res_sid = mysqli_query($conn, "SELECT id FROM additional_services WHERE service_name = '$s_name' LIMIT 1");
                $sid_row = mysqli_fetch_assoc($res_sid);

                if ($sid_row) {
                    $sid = $sid_row['id'];
                    $qty = (int)$s['qty'];

                    $price_snap = (int)($s['cost'] / $qty);

                    $query_serv = "INSERT INTO booking_service (booking_id, additional_service_id, quantity, price_at_purchase, created_at, updated_at) 
                                   VALUES ('$order_id', '$sid', '$qty', '$price_snap', NOW(), NOW())";
                    mysqli_query($conn, $query_serv);
                }
            }
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total_price,
            ],
            'item_details' => [
                [
                    'id' => $id_room,
                    'price' => $total_price,
                    'quantity' => 1,
                    'name' => $room_name
                ]
            ],
            'customer_details' => [
                'first_name' => $_SESSION['nickname'] ?? 'Penyewa',
                'email'      => $_SESSION['email'] ?? 'customer@mail.com',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            mysqli_query($conn, "UPDATE bookings SET payment_token = '$snapToken' WHERE id = '$order_id'");
            echo json_encode(['token' => $snapToken]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Midtrans Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Database Error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['error' => 'Metode akses dilarang']);
}
