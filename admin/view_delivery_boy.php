<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (isset($_GET['delid'])) {
    $id = intval($_GET['delid']);
    mysqli_query($con, "DELETE FROM tblfinalorders WHERE id='$id'");
    $_SESSION['msg'] = "Order deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Assigned Delivery Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include_once('includes/header.php'); ?>
<div id="layoutSidenav">
<?php include_once('includes/sidebar.php'); ?>
<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4 text-center text-muted">All Assigned Delivery Orders</h4>

    <?php if(isset($_SESSION['msg'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Delivery Boy</th>
                    <th>Phone</th>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Shipping</th>
                    <th>Total</th>
                    <th>Delivery Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = mysqli_query($con, "
                SELECT f.id, f.orderid, f.quantity, f.price, f.shippingCharge, f.finalized_at,
                       p.productName, p.productImage1,
                       d.firstname, d.lastname, d.phone
                FROM tblfinalorders f
                JOIN tblproducts p ON f.productid = p.ID
                JOIN tbldeliveryboys d ON f.deliveryid = d.id
                ORDER BY f.orderid DESC
            ");
            $cnt = 1;
            while ($row = mysqli_fetch_array($query)) {
    $total = ($row['price'] * $row['quantity']) + $row['shippingCharge'];
    $productImage = !empty($row['productImage1']) ? 'productimages/' . $row['productImage1'] : 'images/noimage.png';

    // --- Fetch delivery date from tbltracking ---
    $orderId = $row['orderid'];
    $deliveryDate = 'N/A';
    $deliveryQuery = mysqli_query($con, "SELECT StatusDate FROM tbltracking WHERE OrderId = '$orderId' AND status = 'Delivered' ORDER BY id DESC LIMIT 1");
    if ($deliveryData = mysqli_fetch_array($deliveryQuery)) {
        $deliveryDate = date("d-m-Y", strtotime($deliveryData['StatusDate']));
    }
?>
    <tr>
        <td><?= $cnt++ ?></td>
        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= htmlspecialchars($row['orderid']) ?></td>
        <td><?= htmlspecialchars($row['productName']) ?></td>
        <td><img src="<?= $productImage ?>" alt="Product" width="60" height="60" style="object-fit: cover;"></td>
        <td><?= $row['quantity'] ?></td>
        <td>₹<?= number_format($row['price'], 2) ?></td>
        <td>₹<?= number_format($row['shippingCharge'], 2) ?></td>
        <td><strong>₹<?= number_format($total, 2) ?></strong></td>
        <td><?= $deliveryDate ?></td>
        <td>
            <a href="?delid=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')" class="btn btn-sm btn-danger">Delete</a>
        </td>
    </tr>
<?php } ?>

            </tbody>
        </table>
    </div>
</div>
</main>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
</body>
</html>
