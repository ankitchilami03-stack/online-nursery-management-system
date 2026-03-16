<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tbldeliveryboys WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['deliveryboy_id'] = $row['id'];
        $_SESSION['firstname'] = $row['firstname'];
        $_SESSION['lastname'] = $row['lastname'];

        header("Location: delivery/deliver_index.php");
        exit();
    } else {
        echo "<script>alert('Invalid Username or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Boy Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.6s ease-in-out;
        }

        .login-box h4 {
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
            color: #0072ff;
        }

        .form-control:focus {
            border-color: #0072ff;
            box-shadow: 0 0 0 0.2rem rgba(0,114,255,.25);
        }

        .btn-primary {
            background-color: #0072ff;
            border: none;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #005fcc;
        }

        .btn-outline-secondary {
            border-color: #0072ff;
            color: #0072ff;
            margin-top: 10px;
        }

        .btn-outline-secondary:hover {
            background-color: #0072ff;
            color: white;
        }

        .extra-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h4>Delivery Boy Login</h4>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="extra-buttons mt-3">
            <a href="delivery_forget.php" class="btn btn-outline-secondary w-50 me-2">Forgot Password?</a>
            <a href="index.php" class="btn btn-outline-secondary w-50">Back to Home</a>
        </div>
    </div>
</body>
</html>
