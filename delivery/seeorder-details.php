<?php
include('includes/config.php');

// Get userid and orderid from URL
$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
$orderid = isset($_GET['orderid']) ? intval($_GET['orderid']) : 0;




session_start();

if (isset($_SESSION['deliveryboy_id'])) {
    echo "Delivery Boy ID: " . htmlspecialchars($_SESSION['deliveryboy_id']);
} else {
    echo "Delivery Boy not logged in.";
}



// Fetch matching address data
$orderDetails = null;
if ($userid > 0 && $orderid > 0) {
    $query = "SELECT * FROM tblorderaddresses WHERE UserId = ? AND Ordernumber = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $userid, $orderid);
    $stmt->execute();
    $result = $stmt->get_result();
    $orderDetails = $result->fetch_assoc();
}



$userInfo = null;
if ($userid > 0) {
    $userQuery = "SELECT FirstName, LastName FROM users WHERE id = ?";
    $stmt2 = $con->prepare($userQuery);
    $stmt2->bind_param("i", $userid);
    $stmt2->execute();
    $userResult = $stmt2->get_result();
    $userInfo = $userResult->fetch_assoc();
}


$productid = isset($_GET['productid']) ? intval($_GET['productid']) : 0;

$productInfo = null;

if ($productid > 0) {
    $query = "SELECT 
               
                p.category,
                p.productName,
                p.productweight,
                p.productPrice,
                p.productDescription,
                p.productInstruction,
                p.shippingCharge,
                p.productAvailability,
                p.productImage1
              FROM tblproducts p
              JOIN orders o ON o.PId = p.category
              WHERE p.category = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $productid);
    $stmt->execute();
    $result = $stmt->get_result();
    $productInfo = $result->fetch_assoc();
}
$totalPrice = 0;

if ($productInfo && isset($orderDetails['Quantity'])) {
    $productPrice = floatval($productInfo['productPrice']);
    $shippingCharge = floatval($productInfo['shippingCharge']);
    $quantity = intval($orderDetails['Quantity']);

    $totalPrice = ($productPrice * $quantity) + $shippingCharge;
}


$orderid = $_GET['orderid'] ?? '';

$deliveryDate = '';
$deliveryStatus = '';

if (!empty($orderid)) {
    $trackQuery = mysqli_query($con, "
        SELECT status, StatusDate 
        FROM tbltracking 
        WHERE OrderId = '$orderid' 
        AND status IN ('Pickup', 'ontheway', 'Delivered') 
        ORDER BY StatusDate DESC 
        LIMIT 1
    ");

    if ($trackRow = mysqli_fetch_assoc($trackQuery)) {
        $deliveryStatus = ucfirst($trackRow['status']);
        $deliveryDate = date('d-M-Y', strtotime($trackRow['StatusDate']));
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        h2 {
            text-align: center;
            color: blue;
            margin: 30px 0;
        }
        table {
            margin: auto;
            width: 95%;
            border-collapse: collapse;
        }
        th {
            width: 25%;
            background-color: #f8f8f8;
        }
        td, th {
            padding: 12px;
            border: 1px solid #ddd;
        }
    </style>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include_once('includes/navbar.php'); ?>

<div class="container" style="text-align: left; max-width: 900px;">
    <h2>User Details</h2>

    <?php if ($orderDetails) { ?>
        <table class="table table-bordered">
            <tr>
                <th>Order Number</th>
                <td><?= htmlspecialchars($orderDetails['Ordernumber']) ?></td>
                <th>First Name</th>
                <td><?= htmlspecialchars($userInfo['FirstName'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?= htmlspecialchars($userInfo['LastName'] ?? '') ?></td>
                <th>Email</th>
                <td><?= htmlspecialchars($orderDetails['Email']) ?></td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td><?= htmlspecialchars($orderDetails['Phone']) ?></td>
                <th>Flat no./buldng no.</th>
                <td><?= htmlspecialchars($orderDetails['Flatnobuldngno']) ?></td>
            </tr>
            <tr>
                <th>StreetName</th>
                <td><?= htmlspecialchars($orderDetails['StreetName']) ?></td>
                <th>Area</th>
                <td><?= htmlspecialchars($orderDetails['Area']) ?></td>
            </tr>
            <tr>
                <th>Land Mark</th>
                <td><?= htmlspecialchars($orderDetails['Landmark']) ?></td>
                <th>City</th>
                <td><?= htmlspecialchars($orderDetails['City']) ?></td>
            </tr>
            <tr>
                
                <th>Order Date</th>
                <td><?= htmlspecialchars($orderDetails['OrderTime']) ?></td>
				<th>deliveryDate</th>

				 <td><?php echo date('Y-m-d', strtotime($orderDetails['OrderTime'] . ' +15 days')); ?></td>

            </tr>
            <tr>
                
                <th>Zipcode</th>
                <td><?= htmlspecialchars($orderDetails['Zipcode']) ?></td>
				<th>Remark</th>
                <td><?= htmlspecialchars($orderDetails['Remark']) ?></td>
            </tr>
            <tr>
                
                <th>Updation Date</th>
                <td><?= htmlspecialchars($orderDetails['UpdationDate']) ?></td>
            </tr>
           
			
        </table>
		<?php if ($productInfo) { ?>
    <h2 class="text-center">Product Details</h2>
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <tr><th>Product Name</th><td><?= htmlspecialchars($productInfo['productName']) ?></td>
            <th>Product Name</th><td><?= htmlspecialchars($productInfo['productweight']) ?></td>
			
			<tr><th>Payment Method</th>
                <td><?= htmlspecialchars($orderDetails['PaymentMethod']) ?></td>
				<th>Quantity</th>
                <td><?= htmlspecialchars($orderDetails['Quantity']) ?></td>
			
			<tr>
                <th>ProductPrice</th>
                <td><?= htmlspecialchars($productInfo['productPrice']) ?></td>
                <th>ShippingCharge</th>
                <td><?= htmlspecialchars($productInfo['shippingCharge']) ?></td>
				
            </tr>
			<tr>
			
    <th colspan="3">Total Price (Product * Quantity + shippingCharge)</th>
    <td><strong>₹<?= number_format($totalPrice, 2) ?></strong></td>



            </tr>
        </table>
		
		
<!--<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <tr>
        <td>Picked Up</td>
		<td><?php echo htmlspecialchars($deliveryDate); ?></td>
		</tr>
    <tr>
        <td>On the Way</td>
        <td><?= htmlspecialchars($deliveryDate) ?></td>    </tr>
    <tr>
        <td>Deliver Date</td>
        <td><?php echo htmlspecialchars($deliveryDate); ?></td>
    </tr>
</table>-->
    </div>
<?php } ?>

    <?php } else { ?>
        <div class="alert alert-danger text-center">No matching data found.</div>
    <?php } ?>
	<div class="text-center my-4">
    <button class="btn btn-primary" onclick="window.print()">🖨️ Print Bill</button>
</div>



<?php

$orderid = isset($_GET['orderid']) ? $_GET['orderid'] : '';
$alreadyDelivered = false;

// 1. Check if already delivered
if ($orderid != '') {
    $check = mysqli_query($con, "SELECT * FROM tblorderphotos WHERE orderid = '$orderid' AND status = 'Delivered' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $alreadyDelivered = true;
    }
}

// 2. On form submission
if (isset($_POST['submit']) && !$alreadyDelivered) {
    $orderid = $_POST['order_id'];
    $status = $_POST['status'];  
	$deliveryboy_id = $_POST['deliveryboy_id']; // <-- Get delivery boy ID from session
	$reason = isset($_POST['reason']) ? $_POST['reason'] : null;



    $ownerImageName = $_FILES['owner_image']['name'];
    $ownerImageTmp = $_FILES['owner_image']['tmp_name'];
    $uploadFolder = "ownerimages/";
    $ownerImagePath = $uploadFolder . $ownerImageName;

    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    if (move_uploaded_file($ownerImageTmp, $ownerImagePath)) {
    $deliveryboy_id = $_POST['deliveryboy_id'] ?? 0;

$stmt = $con->prepare("INSERT INTO tblorderphotos (orderid, ownerimage, status, reason, deliveryboy_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $orderid, $ownerImageName, $status, $reason, $deliveryboy_id);

        if ($stmt->execute()) {
            echo "<script>alert('Photo captured and marked as Delivered successfully.'); window.location.href='view-orders.php?orderid=$orderid';</script>";
            exit();
        } else {
            echo "<script>alert('Insert failed. Try again.');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed.');</script>";
    }
}
?>

<!-- 3. HTML Form -->
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderid) ?>">

    <div>
        <label>Order ID:</label>
        <input type="text" value="<?= htmlspecialchars($orderid) ?>" readonly>
    </div>

    

    
</form>
<!-- Make sure Bootstrap CSS is included -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<?php if ($orderDetails): ?>

<form method="post" enctype="multipart/form-data" class="p-3 border rounded shadow-sm" style="max-width: 400px; margin: auto;">

    <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderDetails['Ordernumber']) ?>">
	<input type="hidden" name="deliveryboy_id" value="<?= htmlspecialchars($_SESSION['deliveryboy_id']) ?>">


   <div class="mb-3">
    <label class="form-label">Select Delivery Status</label>
    <select name="status" class="form-select form-select-sm" required <?php if ($alreadyDelivered) echo 'disabled'; ?>>
        <option value="">--Select--</option>
        <option value="Delivered" <?= (isset($deliveryStatus) && $deliveryStatus === 'Delivered') ? 'selected' : '' ?>>Delivered</option>
        <option value="NotDelivered" <?= (isset($deliveryStatus) && $deliveryStatus === 'NotDelivered') ? 'selected' : '' ?>>Not Delivered</option>
    </select>
</div>


    <div class="mb-3">
        <label class="form-label">Capture Owner Photo</label>
        <input type="file" name="owner_image" accept="image/*" capture="environment" class="form-control form-control-sm" <?= $alreadyDelivered ? 'disabled' : 'required' ?>>
    </div>
	
	
	 <div class="form-group  mb-3" id="reasonBox">
        <label for="reason" class="form-label">Remark:</label>
        <select name="reason" id="reason" class="form-select form-select-sm">
            <option value="">--Select Reason--</option>
            <option value="">Deliver successfully</option>
            <option value="Plant not well">Plant not well</option>
            <option value="Customer not interested">Customer not interested</option>
            <option value="Damaged product">Damaged product</option>
            <option value="Other">Other</option>
        </select>
    </div>


    <div class="d-grid">
        <button type="submit" name="submit" id="submitBtn" class="btn btn-success btn-sm" 
    <?php if ($alreadyDelivered) echo 'disabled'; ?>>
    <?php echo $alreadyDelivered ? 'Already Delivered' : 'Submit as Delivered'; ?>
</button>

    </div>

</form>
<?php else: ?>
    <p class="text-danger text-center mt-3">Order not found. Check the URL for correct order ID.</p>
<?php endif; ?>


</div>

</body><script>
    const form = document.querySelector("form");
    form.addEventListener("submit", function () {
        const btn = document.getElementById("submitBtn");
        btn.disabled = true;
        btn.innerText = "Submitting...";
    });
</script>
</html>
