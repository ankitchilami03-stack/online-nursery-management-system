<?php
session_start();
include('includes/config.php');

// Check login
if (!isset($_SESSION['deliveryboy_id'])) {
    header("Location: ../login.php");
    exit();
}

$deliveryId = $_SESSION['deliveryboy_id'];
echo "<h3 style='color: red;'>Delivery ID: " . htmlspecialchars($deliveryId) . "</h3>";

// Fetch all pending (NotDelivered) orders for this delivery boy
$query = "SELECT * FROM tblorderphotos WHERE status = 'NotDelivered' AND deliveryboy_id = ?";
$stmt = $con->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $con->error);
}
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Orders (Table)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 40px;
            background-color: #f9f9f9;
            font-family: 'Segoe UI', sans-serif;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            background-color: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .owner-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
		
		.sidebar {
    width: 250px;
    background-color: #343a40;
    color: white;
    height: 100vh;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
}
.sidebar h4 {
    margin-bottom: 30px;
    font-weight: bold;
}
.sidebar a {
    color: #ccc;
    text-decoration: none;
    display: block;
    margin-bottom: 15px;
    font-size: 16px;
    transition: color 0.3s ease;
}
.sidebar a:hover {
    color: #fff;
}
.sidebar i {
    width: 25px;
}

    </style>
	 <meta charset="UTF-8">
    <title>My Finalized Orders</title>
    <!-- Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="sidebar">
    <h4>🚚 Delivery Menu</h4>
    <a href="deliver_index.php"><i class="fas fa-home me-2"></i> Dashboard</a>
    <a href="view-orders.php"><i class="fas fa-truck me-2"></i> Total Orders</a>
    <a href="delivered-orders.php"><i class="fas fa-check-circle me-2"></i> Delivered Orders</a>
	    <a href="pending-orders.php"><i class="fas fa-clock me-2"></i> NotDelivered Orders</a>

    <a href="profile.php"><i class="fas fa-user me-2"></i> Profile</a>
    <a href="changepassword.php"><i class="fas fa-lock me-2"></i> Change Password</a>
    <a href="../index.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>
<h2>📋 Pending Orders (NotDelivered)</h2>

<div class="container mt-5" style="margin-left: 230px;">
    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Owner Image</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['orderid']) ?></td>
                        <td>
                            <img src="ownerimages/<?= htmlspecialchars($row['ownerimage']) ?>" alt="Owner" class="owner-img">
                        </td>
                        <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($row['status']) ?></span></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>

		
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning text-center">
            🚫 No pending (NotDelivered) orders found.
        </div>
    <?php } ?>
</div>

</body>
</html>
