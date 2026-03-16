<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['deliveryboy_id'])) {
    header("Location: ../login.php");
    exit();
}

$deliveryId = $_SESSION['deliveryboy_id'];
$msg = $error = "";

// Handle change password
if (isset($_POST['change'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch existing hashed password
    $query = "SELECT password FROM tbldeliveryboys WHERE id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $deliveryId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($currentPassword, $hashedPassword)) {
        $error = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New password and confirm password do not match.";
    } else {
        $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE tbldeliveryboys SET password=? WHERE id=?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("si", $newHashed, $deliveryId);
        if ($stmt->execute()) {
            $msg = "Password changed successfully!";
        } else {
            $error = "Failed to change password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; padding: 20px; }
        .box {
            background: white; max-width: 500px; margin: auto;
            padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .box h2 { text-align: center; }
        input[type=password], input[type=submit] {
            width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc;
        }
        .success { color: green; text-align: center; }
        .error { color: red; text-align: center; }
    </style>
	 <meta charset="UTF-8">
    <title>My Finalized Orders</title>
    <!-- Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include_once('includes/navbar.php'); ?>


<div class="box">
    <h2>Change Password</h2>
    <?php if ($msg): ?><p class="success"><?php echo $msg; ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <form method="post">
        <label>Current Password</label>
        <input type="password" name="current_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <input type="submit" name="change" value="Change Password">
    </form>
</div>

</body>
</html>
