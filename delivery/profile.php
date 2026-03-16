<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['deliveryboy_id'])) {
    header("Location: ../login.php");
    exit();
}

$deliveryId = $_SESSION['deliveryboy_id'];
$msg = "";

// Handle form submission
if (isset($_POST['update'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    $updateQuery = "UPDATE tbldeliveryboys SET firstname=?, lastname=?, phone=?, age=?, address=? WHERE id=?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sssisi", $firstname, $lastname, $phone, $age, $address, $deliveryId);

    if ($stmt->execute()) {
        $msg = "Profile updated successfully!";
    } else {
        $msg = "Error updating profile.";
    }

    $stmt->close();
}

// Fetch delivery boy profile
$query = "SELECT * FROM tbldeliveryboys WHERE id=?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delivery Boy Profile</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        .profile-box {
            background: #fff;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-box h2 { text-align: center; }
        input[type=text], input[type=number] {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;
        }
        input[type=submit] {
            background: #28a745; color: #fff; border: none; padding: 10px 20px; border-radius: 5px;
            cursor: pointer;
        }
        .msg { text-align: center; color: green; margin-bottom: 10px; }
		
		
		
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

<div class="profile-box">
    <h2>Your Profile</h2>
    <?php if ($msg): ?><div class="msg"><?php echo $msg; ?></div><?php endif; ?>
    <form method="POST">
        <label>First Name</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>

        <label>Age</label>
        <input type="number" name="age" value="<?php echo htmlspecialchars($row['age']); ?>" required>

        <label>Address</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>

        <input type="submit" name="update" value="Update Profile">
    </form>
</div>

</body>
</html>
