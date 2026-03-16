<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit();
}

$msg = "";

if (isset($_POST['reset'])) {
    $email = $_SESSION['reset_email'];
    $newpass = $_POST['password'];
    $confirmpass = $_POST['confirmpassword'];

    if ($newpass !== $confirmpass) {
        $msg = "Passwords do not match.";
    } else {
        $hashedPass = password_hash($newpass, PASSWORD_DEFAULT);

        $sql = "UPDATE tbldeliveryboys SET password = ? WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $hashedPass, $email);
        $stmt->execute();

        unset($_SESSION['reset_email']);
        echo "<script>alert('Password successfully reset!'); window.location='delivery_login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f8e9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 350px;
        }
    </style>
</head>
<body>
<div class="box">
    <h4 class="mb-4 text-center">Reset Your Password</h4>
    <?php if ($msg): ?>
        <div class="alert alert-danger"><?php echo $msg; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirmpassword" class="form-control" required />
        </div>
        <button type="submit" name="reset" class="btn btn-success w-100">Reset Password</button>
    </form>
</div>
</body>
</html>
