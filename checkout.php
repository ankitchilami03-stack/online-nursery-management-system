<?php
session_start();
error_reporting(0);
include_once('includes/config.php');
if (strlen($_SESSION['nmsuid']) == 0) {
    header('location:logout.php');
    exit();
}

if (!isset($_POST['orderid'])) {
    header("Location: cart.php");
    exit();
}

$orderid = intval($_POST['orderid']);
$userid = $_SESSION['nmsuid'];

// Fetch order and product
$query = mysqli_query($con, "SELECT o.id, o.PId, p.productName, p.productImage1, p.productPrice, p.shippingCharge 
    FROM orders o 
    JOIN tblproducts p ON o.PId = p.ID 
    WHERE o.id = '$orderid' AND o.UserId = '$userid' AND o.IsOrderPlaced IS NULL");

$product = mysqli_fetch_assoc($query);
if (!$product) {
    echo "<script>alert('Invalid order ID'); window.location.href='cart.php';</script>";
    exit();
}

$price = $product['productPrice'];
$shipping = $product['shippingCharge'];
$total = $price + $shipping;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents("debug_log.txt", print_r($_POST, true)); // 🔍 Debug log

    if (isset($_POST['placeorder'])) {
        // Your order insertion logic here
    }
}


if (isset($_POST['placeorder'])) {
    $razorpayOrderId = $_POST['razorpay_order_id'] ?? null;

    $paymode = $_POST['paymode'];
    $isOnlinePayment = ($paymode != "Cash on Delivery");

    if ($isOnlinePayment && empty($razorpayOrderId)) {
        echo "<script>alert('Payment failed or canceled'); window.location.href='checkout.php';</script>";
        exit();
    }

    // ✅ Proceed with order placement
 


   $fnaobno = $_POST['flatbldgnumber'];
    $street = $_POST['streename'];
    $area = $_POST['area'];
    $lndmark = $_POST['landmark'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $paymode = $_POST['paymode'];
    $quantity = max(1, intval($_POST['quantity'])); // ✅ sanitize quantity input
    $orderno = mt_rand(100000000, 999999999);
$query1 = "UPDATE orders 
           SET OrderNumber='$orderno', IsOrderPlaced='1', PaymentMethod='$paymode', quantity='$quantity' 
           WHERE id='$orderid' AND UserId='$userid';";

$deliveryDate = date('Y-m-d', strtotime('+15 days'));

$query1 .= "INSERT INTO tblorderaddresses(UserId, Ordernumber, Flatnobuldngno, StreetName, Area, Landmark, City, Zipcode, Phone, Email, PaymentMethod, Quantity, DeliveryDate) 
VALUES('$userid','$orderno','$fnaobno','$street','$area','$lndmark','$city','$zipcode','$phone','$email','$paymode', '$quantity', '$deliveryDate');";

    //$query1 = "UPDATE orders SET OrderNumber='$orderno', IsOrderPlaced='1', PaymentMethod='$paymode' 
             //  WHERE id='$orderid' AND UserId='$userid';";

    //$query1 .= "INSERT INTO tblorderaddresses(UserId, Ordernumber, Flatnobuldngno, StreetName, Area, Landmark, City, Zipcode, Phone, Email, PaymentMethod, Quantity) 
           // VALUES('$userid','$orderno','$fnaobno','$street','$area','$lndmark','$city','$zipcode','$phone','$email','$paymode', '$quantity');";

    // ✅ update quantity in tblorderdetails
    //$query1 .= "UPDATE tblorderdetails SET Quantity='$quantity' WHERE OrderNumber='$orderno';";
    //$query1 .= "UPDATE orders SET quantity='$quantity' WHERE OrderNumber='$orderno';";

    if (mysqli_multi_query($con, $query1)) {
  $_SESSION['last_order_number'] = $orderno;
$_SESSION['last_order_total'] = $price * $quantity + $shipping;
header("Location: payment-success.php");
exit();

} else {
    echo "MySQL Error: " . mysqli_error($con);
    exit();
}

}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Nursery Management System | Checkout</title>
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href='http://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/jquery1.min.js"></script>
    <link href="css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="js/megamenu.js"></script>
    <script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
    <script src="js/jquery.easydropdown.js"></script>
</head>
<body>

<?php include_once('includes/header.php'); ?>
<div class="login">
    <div class="wrap">
        <div class="col_1_of_login span_1_of_login">
            <h3>Review Product</h3>
            <table border="2" class="table table-responsive">
                <tr style="font-size: 20px;color: blue;">
                    <th>Image</th>
                    <th>Product</th>
                    <th>DeliveryDate</th>
                    <th>Price</th>
                    <th>Shipping</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td><img src="admin/productimages/<?php echo $product['productImage1'];?>" height="150" alt=""></td>
                    <td><?php echo $product['productName']; ?></td>
					<td>
        <?php
            $deliveryDate = date('d-m-Y', strtotime('+15 days'));
            echo $deliveryDate;
        ?>
    </td>
                    <td>₹<?php echo $price; ?></td>
                    <td>₹<?php echo $shipping; ?></td>
                    <td id="display-total">₹<?php echo $total; ?></td>
                </tr>
            </table>
        </div>

        <div class="col_1_of_login span_1_of_login">
            <div class="login-title">
                <h4 class="title">Billing Details</h4>
                <div id="loginbox" class="loginbox">
                    <form method="post" id="checkout-form">
                        <input type="hidden" name="orderid" value="<?php echo $orderid; ?>">
						<input type="hidden" name="placeorder" value="1">

                        <fieldset class="input">
                            <p><label>Flat or Building Number *</label><input type="text" name="flatbldgnumber" required class="inputbox"></p>
                            <p><label>Street Name *</label><input type="text" name="streename" required class="inputbox"></p>
                            <p><label>Area *</label><input type="text" name="area" required class="inputbox"></p>
                            <p><label>Zip/Postal Code *</label><input type="text" name="zipcode" maxlength="6" required class="inputbox"></p>
                            <p><label>Landmark</label><input type="text" name="landmark" class="inputbox"></p>
                            <p><label>Phone</label><input type="text" name="phone" maxlength="10" pattern="[0-9]+" class="inputbox"></p>
                            <p><label>Email *</label><input type="email" name="email" required class="inputbox"></p>

                            <!-- ✅ Quantity input added here -->
                            <p><label>Quantity *</label>
                            <input type="number" id="qty" name="quantity" min="1" value="1" class="inputbox" required></p>

                            <p>
                                <label>Payment Method *</label>
                                <select name="paymode" class="inputbox" required>
                                    <option value="">Select</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </p>
							<input type="hidden" id="razorpay_order_id" name="razorpay_order_id">


                            <!-- ✅ Subtotal/Total updated with IDs for JS -->
                            <h4>Subtotal: ₹<span id="subtotal"><?php echo $price; ?></span></h4>
                            <p>+ Shipping: ₹<span id="shipping"><?php echo $shipping; ?></span></p>
                            <h3>Total: ₹<span id="grandtotal"><?php echo $total; ?></span></h3>

                            <input type="submit" name="placeorder" class="button" value="PLACE ORDER">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<!-- ✅ Script to auto-update subtotal and total -->
<script>
    const price = <?php echo $price; ?>;
    const shipping = <?php echo $shipping; ?>;

    document.getElementById('qty').addEventListener('input', function () {
        let qty = parseInt(this.value);
        if (isNaN(qty) || qty < 1) qty = 1;

        let subtotal = price * qty;
        let grandTotal = subtotal + shipping;

        document.getElementById('subtotal').textContent = subtotal;
        document.getElementById('grandtotal').textContent = grandTotal;
        document.getElementById('display-total').textContent = '₹' + grandTotal;
    });
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const form = document.querySelector('form');
    const paymodeSelect = document.querySelector('[name="paymode"]');

    form.addEventListener('submit', async function(e) {
        if (paymodeSelect.value === 'Cash on Delivery') {
            // Allow normal submission
            return true;
        }

        e.preventDefault(); // prevent default form

        const qty = document.getElementById('qty').value;
        const price = <?php echo $price; ?>;
        const shipping = <?php echo $shipping; ?>;
        const total = (price * qty) + shipping;

        const response = await fetch('orderpay.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ amount: total })
        });

        const data = await response.json();

        const options = {
            "key": "rzp_test_PQxzJ4X8vUUnHH",
            "amount": total * 100,
            "currency": "INR",
            "name": "Nursery Management",
            "description": "Product Purchase",
            "order_id": data.order_id,
            "handler": function (response) {
                // On success → add order_id and submit form
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                form.submit();
            },
            "prefill": {
                "email": document.querySelector('[name="email"]').value,
                "contact": document.querySelector('[name="phone"]').value
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    });
</script>



</body>
</html>
