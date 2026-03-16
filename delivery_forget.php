<?php
session_start();
include('includes/config.php');

$msg = "";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM tbldeliveryboys WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $_SESSION['reset_email'] = $email;
        header("Location: reset-password.php");
        exit();
    } else {
        $msg = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e3f2fd;
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
    <h4 class="mb-4 text-center">Forgot Password</h4>
    <?php if ($msg): ?>
        <div class="alert alert-danger"><?php echo $msg; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" required />
        </div>
        <button type="submit" name="submit" class="btn btn-primary w-100">Continue</button>
		<div class="extra-buttons mt-3 ms-8">
            <a href="index.php" class="btn btn-outline-secondary w-50">Back to Home</a>
        </div>
    </form>
</div>
</body>
</html>
