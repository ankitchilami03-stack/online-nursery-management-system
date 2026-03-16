<?php
session_start();
error_reporting(0);
include_once('includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $age = $_POST['age'];
        $password = $_POST['password'];
		$deliveryid=$_POST['deliveryid'];
        $confirmpassword = $_POST['confirmpassword'];


        // Check if passwords match
        if ($password !== $confirmpassword) {
            echo "<script>alert('Password and Confirm Password do not match.');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = mysqli_query($con, "INSERT INTO tbldeliveryboys(firstname, lastname, email, phone, address, age, password,deliveryid) 
            VALUES('$fname', '$lname', '$email', '$phone', '$address', '$age', '$hashedPassword','$deliveryid')");

            if ($query) {
                echo "<script>alert('Delivery boy added successfully');</script>";
                echo "<script>window.location.href='add_delivery_boy.php';</script>";
            } else {
                echo "<script>alert('Something went wrong. Try again.');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Delivery Boy</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
    <body class="sb-nav-fixed">
   <?php include_once('includes/header.php');?>
        <div id="layoutSidenav">
          <?php include_once('includes/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                    <h1 class="mt-4">Add Delivery Boy</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="post">
							<div class="row mb-3">
                                    <div class="col-2">Delivery Id</div>
                                    <div class="col-4"><input type="text" name="deliveryid" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">First Name</div>
                                    <div class="col-4"><input type="text" name="firstname" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Last Name</div>
                                    <div class="col-4"><input type="text" name="lastname" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Email</div>
                                    <div class="col-4"><input type="email" name="email" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Phone</div>
                                    <div class="col-4"><input type="text" name="phone" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Address</div>
                                    <div class="col-4"><textarea name="address" class="form-control" required></textarea></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Age</div>
                                    <div class="col-4"><input type="number" name="age" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Password</div>
                                    <div class="col-4"><input type="password" name="password" class="form-control" required></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-2">Confirm Password</div>
                                    <div class="col-4"><input type="password" name="confirmpassword" class="form-control" required></div>
                                </div>
                                <div class="row">
                                    <div class="col-2"><button type="submit" name="submit" class="btn btn-primary">Submit</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
