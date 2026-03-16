<?php
include('includes/config.php');

$deliveryId = $_GET['did'] ?? null;

$orderDetails = [];
$selectedOrderNumber = $_POST['ordernumber'] ?? '';

if (!empty($selectedOrderNumber)) {
    $query = mysqli_query($con, "SELECT * FROM tblorderaddresses WHERE Ordernumber = '$selectedOrderNumber'");
    $orderDetails = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fetch Order by Order Number</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php include_once('includes/header.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
			<?php if ($deliveryId): ?>
    <div class="alert alert-info text-center mt-4">
        <strong>Selected Delivery Boy ID:</strong> <?= htmlspecialchars($deliveryId) ?>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <h4>Search Order by Order Number</h4>
    <form method="post">
        <div class="form-group">
            <label>Select Order Number:</label>
<select name="ordernumber" class="form-control" required>
    <option value="">Select Order</option>
    <?php
    $result = mysqli_query($con, "
        SELECT DISTINCT o.Ordernumber
        FROM tblorderaddresses o
        INNER JOIN tbltracking t ON t.orderid = o.Ordernumber
        WHERE t.status = 'On The Way'
        AND NOT EXISTS (
            SELECT 1 FROM tblfinalorders f WHERE f.orderid = o.Ordernumber
        )
    ");

    while ($row = mysqli_fetch_assoc($result)) {
        $selected = ($row['Ordernumber'] == $selectedOrderNumber) ? "selected" : "";
        echo "<option value='{$row['Ordernumber']}' $selected>{$row['Ordernumber']}</option>";
    }
    ?>
</select>

        </div>
        <button type="submit" class="btn btn-primary">Fetch Details</button>
    </form>

    <?php if (!empty($orderDetails)) { ?>
        <div class="mt-4">
            <h5>Order Details for Order Number: <?= htmlspecialchars($selectedOrderNumber) ?></h5>
            <table class="table table-bordered">
                <tr><th>ID</th><td><?= $orderDetails['ID'] ?></td>
                <th>UserId</th><td><?= $orderDetails['UserId'] ?></td></tr>
                <tr><th>Flat/Building No</th><td><?= $orderDetails['Flatnobuldngno'] ?></td>
                <th>Street Name</th><td><?= $orderDetails['StreetName'] ?></td></tr>
                <tr><th>Area</th><td><?= $orderDetails['Area'] ?></td>
                <th>Landmark</th><td><?= $orderDetails['Landmark'] ?></td></tr>
                <tr><th>City</th><td><?= $orderDetails['City'] ?></td>
                <th>Zipcode</th><td><?= $orderDetails['Zipcode'] ?></td></tr>
                <tr><th>Phone</th><td><?= $orderDetails['Phone'] ?></td>
                <th>Email</th><td><?= $orderDetails['Email'] ?></td></tr>
                <tr><th>Payment Method</th><td><?= $orderDetails['PaymentMethod'] ?></td>
                <th>Order Time</th><td><?= $orderDetails['OrderTime'] ?></td></tr>
                <tr><th>Status</th><td><?= $orderDetails['Status'] ?></td>
                <th>Remark</th><td><?= $orderDetails['Remark'] ?></td></tr>
                <tr><th>Updation Date</th><td><?= $orderDetails['UpdationDate'] ?></td>
                <th>Quantity</th><td><?= $orderDetails['Quantity'] ?></td></tr>
            </table>
			<!-- Add below this closing div inside the if (!empty($orderDetails)) block -->
<form method="post">
    <input type="hidden" name="ordernumber" value="<?= htmlspecialchars($selectedOrderNumber) ?>">
    <input type="hidden" name="userid" value="<?= $orderDetails['UserId'] ?>">
    <button type="submit" name="fetchplant" class="btn btn-primary mt-2">Fetch Plant Details</button>
</form>

        </div>

    <?php } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
        <div class="alert alert-danger mt-3">No order found for this number.</div>
    <?php } ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
<?php
if (isset($_POST['fetchplant']) && !empty($_POST['userid']) && !empty($_POST['ordernumber'])) {
    $userId = intval($_POST['userid']);
    $orderNumber = mysqli_real_escape_string($con, $_POST['ordernumber']);

    // Get Quantity from tblorderaddresses
    $qtyResult = mysqli_query($con, "SELECT Quantity FROM tblorderaddresses WHERE UserId = $userId AND Ordernumber = '$orderNumber'");
    $qtyRow = mysqli_fetch_assoc($qtyResult);
    $quantity = $qtyRow['Quantity'] ?? 1;
	
	

   $productQuery = mysqli_query($con, "
    SELECT 
        p.ID,
        p.category,
        p.productName,
        p.productweight,
        p.productPrice,
        p.shippingCharge,
        p.productAvailability,
        p.productImage1,
   
        o.Quantity
    FROM orders o
    JOIN tblproducts p ON p.ID = o.PId
    WHERE o.OrderNumber = '$orderNumber'
    LIMIT 1
");

    if (mysqli_num_rows($productQuery) > 0) {
		
        echo '<div class="container mt-4">';
        echo '<h5>Matched Plant Details from tblproducts</h5>';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead><tr>
                <th>ID</th><th>Category</th><th>Product Name</th><th>Weight</th><th>Unit Price</th><th>Quantity</th>
                <th>Shipping</th><th>Total Price</th><th>Availability</th>
                <th>Image 1</th></th><th>finalizeOrder</th></th>
              </tr></thead><tbody>';

        while ($row = mysqli_fetch_assoc($productQuery)) {
$unitPrice = (float)$row['productPrice'];
$quantity = (int)$row['Quantity'];
$shipping = (float)$row['shippingCharge'];

$totalPrice = ($unitPrice * $quantity) + $shipping;

            
			  echo "<tr>
    <td>{$row['ID']}</td>
    <td>" . htmlspecialchars($row['category']) . "</td>
    <td>" . htmlspecialchars($row['productName']) . "</td>
    <td>" . htmlspecialchars($row['productweight']) . "</td>
    <td>₹" . htmlspecialchars($row['productPrice']) . "</td>
    <td>$quantity</td>
    <td>₹" . htmlspecialchars($row['shippingCharge']) . "</td>
    <td><strong>₹" . number_format($totalPrice, 2) . "</strong></td>
    <td>" . htmlspecialchars($row['productAvailability']) . "</td>
    <td><img src='productimages/" . htmlspecialchars($row['productImage1']) . "' width='70' height='70' alt='" . htmlspecialchars($row['productName']) . "'></td>

    <td>
        <form method='post'>
            <input type='hidden' name='deliveryid' value='" . htmlspecialchars($deliveryId) . "'>
            <input type='hidden' name='orderid' value='" . htmlspecialchars($selectedOrderNumber) . "'>
            <input type='hidden' name='userid' value='" . htmlspecialchars($orderDetails['UserId']) . "'>
            <input type='hidden' name='productid' value='" . $row['ID'] . "'>
            <input type='hidden' name='quantity' value='" . $row['Quantity'] . "'>
            <input type='hidden' name='price' value='" . $row['productPrice'] . "'>
            <input type='hidden' name='shippingCharge' value='" . $row['shippingCharge'] . "'>
            <button type='submit' name='finalizeOrder' class='btn btn-success btn-sm'>Finalize Order</button>
        </form>
    </td>
</tr>";


        }

        echo '</tbody></table></div>';
    } else {
        echo '<div class="container mt-3 alert alert-warning">No matching product found for this User ID.</div>';
    }
}
?>

<?php
if (isset($_POST['finalizeOrder'])) {
    $deliveryId = $_POST['deliveryid'];
    $orderId = $_POST['orderid'];
    $userId = $_POST['userid'];
    $productId = $_POST['productid'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $shipping = $_POST['shippingCharge'];

    // Insert into tblfinalorders
    $insertQuery = mysqli_query($con, "INSERT INTO tblfinalorders 
        (deliveryid, orderid, userid, productid, quantity, price, shippingCharge) 
        VALUES 
        ('$deliveryId', '$orderId', '$userId', '$productId', '$quantity', '$price', '$shipping')");

    if ($insertQuery) {
        echo "<script>alert('Order finalized and stored successfully');</script>";
    } else {
        echo "<script>alert('Error while saving to database');</script>";
    }
}
?>
