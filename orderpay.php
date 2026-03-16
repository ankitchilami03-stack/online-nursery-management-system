<?php
session_start();
require('includes/config.php');

header('Content-Type: application/json');

// Razorpay credentials
$key_id = "rzp_test_PQxzJ4X8vUUnHH";
$key_secret = "jkhjiexE0Qx16KSBVFNBWzMf";

require('razorpay/Razorpay.php');
use Razorpay\Api\Api;

// Get POST data from fetch
$data = json_decode(file_get_contents('php://input'), true);
$amount = $data['amount']; // in INR

$api = new Api($key_id, $key_secret);

$order = $api->order->create([
    'receipt' => 'rcptid_' . rand(10000, 99999),
    'amount' => $amount * 100, // Amount in paise
    'currency' => 'INR',
    'payment_capture' => 1
]);

echo json_encode([
    'order_id' => $order['id'],
    'key' => $key_id
]);
?>
