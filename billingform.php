<?php
session_start();
error_reporting(0);
include_once('includes/config.php');

if (strlen($_SESSION['nmsuid']) == 0) {
    header('location:logout.php');
    exit();
}

$pid = $_POST['pid'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;
$productPrice = $_POST['productPrice'] ?? 0;

$product = mysqli_fetch_assoc(mysqli_query($con, "SELECT productName, shippingCharge FROM tblproducts WHERE ID = '$pid'"));
$productName = $product['productName'];
$shippingCharge = $product['shippingCharge'];

$subtotal = $productPrice * $quantity;
$grandTotal = $subtotal + $shippingCharge;

if (isset($_POST['placeorder'])) {
    $fnaobno = $_POST['flatbldgnumber'];
    $street = $_POST['streename'];
    $area = $_POST['area'];
    $lndmark = $_POST['landmark'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $paymode = $_POST['paymode'];
    $userid = $_SESSION['nmsuid'];
    $orderno = mt_rand(100000000, 999999999);
    $note = $_POST['note'];
    $delivery = $_POST['delivery'];

    $query="update orders set OrderNumber='$orderno',IsOrderPlaced='1',PaymentMethod='$paymode' where UserId='$userid' and IsOrderPlaced is null;";
$query.="insert into tblorderaddresses(UserId,Ordernumber,Flatnobuldngno,StreetName,Area,Landmark,City,Zipcode,Phone,Email,PaymentMethod) values('$userid','$orderno','$fnaobno','$street','$area','$lndmark','$city','$zipcode','$phone','$email','$paymode');";

$result = mysqli_multi_query($con, $query);
if ($result) {

echo '<script>alert("Your order placed successfully. Order number is "+"'.$orderno.'")</script>';
echo "<script>window.location.href='my-orders.php'</script>";

}
}    
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Nursery Plant Billing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="js/jquery1.min.js"></script>
<!-- start menu -->
<link href="css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
<script src="js/jquery.easydropdown.js"></script>
	
    <!-- Embedded CSS -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5fff5;
        }

        .container {
            max-width: 800px;
            background: white;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,128,0,0.1);
            border-left: 8px solid green;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 25px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="tel"],
        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background: #f0fff0;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
        }

        form label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #444;
        }

        .summary {
            background-color: #e8f5e9;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #4caf50;
            border-radius: 8px;
        }

        .summary p {
            margin: 8px 0;
        }

        .summary h3 {
            margin-top: 15px;
            color: #1b5e20;
        }

        .button {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 14px;
            font-size: 16px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .button:hover {
            background-color: #256d27;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

	<?php include_once('includes/header.php');?>

<div class="container">
    <h2>Billing Details for: <?php echo htmlspecialchars($productName); ?></h2>
    <form method="post" action="">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
        <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
        <input type="hidden" name="productPrice" value="<?php echo $productPrice; ?>">

        <div class="summary">
            <p><strong>Product:</strong> <?php echo $productName; ?></p>
            <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
            <p><strong>Each Price:</strong> ₹<?php echo number_format($productPrice, 2); ?></p>
            <p><strong>Subtotal:</strong> ₹<?php echo number_format($subtotal, 2); ?></p>
            <p><strong>Shipping Charge:</strong> ₹<?php echo number_format($shippingCharge, 2); ?></p>
            <h3>Grand Total: ₹<?php echo number_format($grandTotal, 2); ?></h3>
        </div>

        <label>Flat/Building No</label>
        <input type="text" name="flatbldgnumber" required>

        <label>Street Name</label>
        <input type="text" name="streename" required>

        <label>Area</label>
        <input type="text" name="area" required>

        <label>Landmark</label>
        <input type="text" name="landmark">

        <label>City</label>
        <input type="text" name="city" required>

        <label>Pincode</label>
        <input type="text" name="zipcode" required>

        <label>Phone</label>
        <input type="tel" name="phone" maxlength="10" pattern="[0-9]{10}" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Payment Method</label>
        <select name="paymode" required>
            <option value="">-- Select Payment Method --</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Cash on Delivery">Cash on Delivery</option>
            <option value="E-Wallet">E-Wallet</option>
        </select>

        <label>Expected Delivery Date</label>
        <input type="date" name="delivery" value="<?php echo date('Y-m-d', strtotime('+10 days')); ?>" readonly>

        <label>Special Instructions</label>
        <textarea name="note" rows="3" placeholder="Any special instructions..."></textarea>

        <input type="submit" name="placeorder" value="PLACE ORDER" class="button">
    </form>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
</html>
