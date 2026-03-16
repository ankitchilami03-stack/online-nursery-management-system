<?php
session_start();

// Check if data exists
if (!isset($_SESSION['last_order_number']) || !isset($_SESSION['last_order_total'])) {
    header('Location: my-orders.php');
    exit();
}

$orderNo = $_SESSION['last_order_number'];
$total = $_SESSION['last_order_total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <style>
        body { font-family: Arial; background: #f0fff0; padding: 50px; }
        .box {
            background: white; padding: 20px; border-radius: 10px;
            max-width: 500px; margin: auto; box-shadow: 0 0 10px #ccc;
        }
        .box h2 { color: green; }
    </style>
</head>
<body>
    <div class="box">
        <h2>✅ Payment Successful</h2>
        <p>Thank you for your payment!</p>
        <p><strong>Order Number:</strong> <?php echo $orderNo; ?></p>
        <p><strong>Total Paid:</strong> ₹<?php echo $total; ?></p>
        <br>
        <a href="my-orders.php">📦 View Your Orders</a>
    </div>
</body>
</html>

<?php
// Clear session data after displaying
unset($_SESSION['last_order_number']);
unset($_SESSION['last_order_total']);
?>
