<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Check if delivery boy is logged in
if (!isset($_SESSION['deliveryboy_id'])) {
    header("Location: logout.php");
    exit();
}
$deliveryId = $_SESSION['deliveryboy_id'];

// Handle delete request (optional)
if (isset($_GET['delid'])) {
    $id = intval($_GET['delid']);
    $stmt = $con->prepare("DELETE FROM tblfinalorders WHERE id = ? AND deliveryid = ?");
    $stmt->bind_param("ii", $id, $deliveryId);
    $stmt->execute();
    $_SESSION['msg'] = "Order deleted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Finalized Orders</title>
    <!-- Bootstrap + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
<?php include_once('includes/navbar.php'); ?>

<div class="container mt-5" style="margin-left: 230px;">
        <h2 class="mb-4 text-center">My Finalized Orders</h2>

        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-success text-center"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
        <?php } ?>

        <div class="table-responsive container  ms-9">
            <table class="table table-bordered table-striped table-hover ">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Delivery ID</th>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Price (₹)</th>
                        <th>Shipping Charge (₹)</th>
                        <th>Total Price (₹)</th> <!-- New column -->
                        <th>OrderDate(₹)</th> <!-- New column -->
                        <th>DeliveryDate (₹)</th> <!-- New column -->
                        <th>Finalized At</th>
                        <th>Status</th>
						<th>Delivery Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM tblfinalorders WHERE deliveryid = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("i", $deliveryId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $cnt = 1;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $totalPrice = ($row['quantity'] * $row['price']) + $row['shippingCharge'];
							
							 $orderid = $row['orderid'];
							 
							 $deliveryStatus = "Please Deliver"; // Default
// status delivery not delivery  show query
$statusQuery = $con->prepare("SELECT status FROM tblorderphotos WHERE orderid = ? LIMIT 1");
$statusQuery->bind_param("s", $orderid);
$statusQuery->execute();
$statusResult = $statusQuery->get_result();

if ($statusRow = $statusResult->fetch_assoc()) {
    $deliveryStatus = $statusRow['status'];
}
$statusQuery->close();



$deliveryQuery = $con->prepare("SELECT DATE_ADD(OrderTime, INTERVAL 15 DAY) AS DeliveryDate FROM tblorderaddresses WHERE Ordernumber = ?");
$deliveryQuery->bind_param("s", $orderid);
$deliveryQuery->execute();
$deliveryResult = $deliveryQuery->get_result();
if ($deliveryRow = $deliveryResult->fetch_assoc()) {
    $deliveryDate = $deliveryRow['DeliveryDate'];
} else {
    $deliveryDate = "N/A";
}


$orderid = $row['orderid'];
$deliveryQuery = $con->prepare("SELECT OrderTime, DATE_ADD(OrderTime, INTERVAL 15 DAY) AS DeliveryDate FROM tblorderaddresses WHERE Ordernumber = ?");
$deliveryQuery->bind_param("s", $orderid);
$deliveryQuery->execute();
$deliveryResult = $deliveryQuery->get_result();
if ($deliveryRow = $deliveryResult->fetch_assoc()) 
{
	 $OrderTime = date("d-m-Y", strtotime($deliveryRow['OrderTime']));
    $deliveryDate = date("d-m-Y", strtotime($deliveryRow['DeliveryDate']));
       // Calculate Delivery Date
} else {
    $OrderTime = "N/A";
    $deliveryDate = "N/A";
}


							
							

                            echo "<tr>";
                            echo "<td>" . $cnt++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['deliveryid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['orderid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['userid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['productid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                            echo "<td>₹" . number_format($row['price'], 2) . "</td>";
                            echo "<td>₹" . number_format($row['shippingCharge'], 2) . "</td>";
                            echo "<td><strong>₹" . number_format($totalPrice, 2) . "</strong></td>";
							echo "<td>" . htmlspecialchars($OrderTime) . "</td>";
							echo "<td>" . htmlspecialchars($deliveryDate) . "</td>";


                            echo "<td>" . htmlspecialchars($row['finalized_at']) . "</td>";
echo "<td><a href='seeorder-details.php?orderid=" . $row['orderid'] . "&userid=" . $row['userid'] . "&productid=" . $row['productid'] . "' class='btn btn-sm btn-info'>View Details</a></td>";
echo "<td>" . htmlspecialchars($deliveryStatus) . "</td>";


                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' class='text-center text-danger'>No finalized orders found for you</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
