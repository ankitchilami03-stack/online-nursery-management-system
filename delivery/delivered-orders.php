<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$deliveryId = $_SESSION['deliveryboy_id'];

// Fetch delivered orders
$query = "SELECT * FROM tblorderphotos WHERE deliveryboy_id = ? AND status = 'Delivered'";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delivered Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
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
<body>

<div class="sidebar">
    <h4>🚚 Delivery Menu</h4>
    <a href="deliver_index.php"><i class="fas fa-home me-2"></i> Dashboard</a>
    <a href="view-orders.php"><i class="fas fa-truck me-2"></i> Total Orders</a>
    <a href="delivered-orders.php"><i class="fas fa-check-circle me-2"></i> Delivered Orders</a>
	    <a href="pending-orders.php"><i class="fas fa-clock me-2"></i> NotDelievered Orders</a>

    <a href="profile.php"><i class="fas fa-user me-2"></i> Profile</a>
    <a href="changepassword.php"><i class="fas fa-lock me-2"></i> Change Password</a>
    <a href="../index.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="container mt-5" style="margin-left: 250px;">
    <h2 class="mb-4">Delivered Orders</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
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
                echo "<tr>";
                echo "<td>" . $count++ . "</td>";
                echo "<td>" . htmlspecialchars($row['orderid']) . "</td>";
            echo "<td><img src='ownerimages/" . htmlspecialchars($row['ownerimage']) . "' width='100'></td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";

                echo "</tr>";
            }

            if ($count == 1) {
                echo "<tr><td colspan='5' class='text-center'>No delivered orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
