<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Nursery Management System || Not Delivered Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<?php include_once('includes/header.php'); ?>
<div id="layoutSidenav">
    <?php include_once('includes/sidebar.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Not Delivered Orders</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Not Delivered Orders</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Order Details
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Order Date</th>
                                    <th>Status Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
									<th>Reason</th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Order Date</th>
                                    <th>Status Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
									<th>Reason</th>

                                </tr>
                            </tfoot>
                            <tbody>
<?php
// Fetch orders with NotDelivered status from tblorderphotos
$query = "
SELECT oa.*, u.FirstName, u.LastName, u.MobileNumber, u.Email, tp.created_at AS status_date, tp.status, tp.reason 
FROM tblorderaddresses oa
JOIN users u ON u.id = oa.UserId
JOIN tblorderphotos tp ON tp.orderid = oa.Ordernumber
WHERE tp.status = 'NotDelivered'
ORDER BY tp.created_at DESC
";


$ret = mysqli_query($con, $query);
$cnt = 1;

while ($row = mysqli_fetch_array($ret)) {
?>
<tr>
    <td><?php echo $cnt++; ?></td>
    <td><?php echo $row['Ordernumber']; ?></td>
    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
    <td><?php echo $row['MobileNumber']; ?></td>
    <td><?php echo $row['Email']; ?></td>

    <!-- Order Date -->
    <td><?php echo date('d-m-Y', strtotime($row['OrderTime'])); ?></td>

    <!-- Not Delivered Date -->
    <td><?php echo date('d-m-Y', strtotime($row['status_date'])); ?></td>

    <!-- Status -->
    <td><?php echo htmlspecialchars($row['status']); ?></td>

    <!-- View Button -->
    <td>
        <a href="view-order.php?orderid=<?php echo $row['Ordernumber']; ?>" class="btn btn-primary">View Details</a>
    </td>
	<td><?php echo htmlspecialchars($row['reason']); ?></td>

</tr>
<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
<?php include_once('includes/footer.php'); ?>
    </div>
</div>
<script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
</body>
</html>
<?php } ?>
