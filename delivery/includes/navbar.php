<html>
<head>
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
</body>
</html>