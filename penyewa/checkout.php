<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';

\Midtrans\Config::$serverKey = 'Mid-server-QWIUeWSf_M92Na-vnWXvLS5E';  
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_room = $_POST['id_room'];
    $total_price = (int)$_POST['total_price'];
    $order_id = 'KOS-' . time(); 
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => $total_price,
    ];

    $item_details = [
        [
            'id' => $id_room,
            'price' => $total_price,
            'quantity' => 1,
            'name' => "Sewa Kamar No. " . $id_room
        ]
    ];

    $params = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo json_encode(['token' => $snapToken]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
