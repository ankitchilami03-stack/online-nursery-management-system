<?php
session_start();
$orderNo = $_SESSION['show_order_number'] ?? 'N/A';
$amount = $_SESSION['show_amount_paid'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0fdf4;
            text-align: center;
            padding: 50px;
        }
        .box {
            background: #e6ffee;
            display: inline-block;
            padding: 30px;
            border: 2px solid #4CAF50;
            border-radius: 10px;
        }
        h2 { color: #4CAF50; }
    </style>
</head>
<body>
    <div class="box">
        <h2>✅ Payment Successful</h2>
        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($orderNo); ?></p>
        <p><strong>Amount Paid:</strong> ₹<?php echo number_format($amount, 2); ?></p>
        <p>Thank you for shopping with us! 🌱</p>
    </div>
</body>
</html>
