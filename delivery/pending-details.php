<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Validate and get values from URL
$orderid = isset($_GET['orderid']) ? intval($_GET['orderid']) : 0;
$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
$productid = isset($_GET['productid']) ? intval($_GET['productid']) : 0;

if (!$orderid || !$userid || !$productid) {
    echo "Missing data.";
    exit();
}

// Fetch user details
$user_query = mysqli_query($con, "SELECT firstname, lastname, phone FROM users WHERE id='$userid'");
$user = mysqli_fetch_assoc($user_query);

// Fetch address
$address_query = mysqli_query($con, "SELECT shippingAddress, landmark, city, state, pincode FROM tblorderaddresses WHERE orderid='$orderid' AND UserId='$userid'");
$address = mysqli_fetch_assoc($address_query);

// Fetch plant (product) details
$product_query = mysqli_query($con, "SELECT productName, productCompany, productPrice, productImage1 FROM products WHERE id='$productid'");
$product = mysqli_fetch_assoc($product_query);

// Fetch delivery photo and status
$photo_query = mysqli_query($con, "SELECT status, image, created_at FROM tblorderphotos WHERE orderid='$orderid'");
$photo = mysqli_fetch_assoc($photo_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Order Details</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Order Details</h2>

    <!-- User Info -->
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">User Information</div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        </div>
    </div>

    <!-- Address -->
    <div class="card mb-3">
        <div class="card-header bg-success text-white">Delivery Address</div>
        <div class="card-body">
            <p><strong>Address:</strong> <?= htmlspecialchars($address['shippingAddress']) ?>, <?= htmlspecialchars($address['landmark']) ?></p>
            <p><strong>City/State:</strong> <?= htmlspecialchars($address['city']) ?> / <?= htmlspecialchars($address['state']) ?></p>
            <p><strong>Pincode:</strong> <?= htmlspecialchars($address['pincode']) ?></p>
        </div>
    </div>

    <!-- Product Info -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Plant Information</div>
        <div class="card-body">
            <p><strong>Name:</strong> <?= htmlspecialchars($product['productName']) ?></p>
            <p><strong>Company:</strong> <?= htmlspecialchars($product['productCompany']) ?></p>
            <p><strong>Price:</strong> ₹<?= htmlspecialchars($product['productPrice']) ?></p>
            <p><img src="../admin/productimages/<?= htmlspecialchars($product['productImage1']) ?>" width="150" height="150" alt="Plant Image"></p>
        </div>
    </div>

    <!-- Delivery Status -->
    <div class="card mb-3">
        <div class="card-header bg-warning text-dark">Delivery Status</div>
        <div class="card-body">
            <p><strong>Status:</strong> <?= htmlspecialchars($photo['status']) ?></p>
            <p><strong>Delivered On:</strong> <?= htmlspecialchars($photo['created_at']) ?></p>
            <?php if (!empty($photo['image'])): ?>
                <p><img src="uploads/<?= htmlspecialchars($photo['image']) ?>" width="200" height="200" alt="Delivery Photo"></p>
            <?php else: ?>
                <p>No delivery image uploaded.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center">
        <a href="pending-orders.php" class="btn btn-secondary">Back to Orders</a>
    </div>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
