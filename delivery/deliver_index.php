<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$deliveryId = intval($_SESSION['deliveryboy_id']);


// Initialize variables
$totalDelivered = $totalPending = $totalCancelled = $totalOrders = 0;


// Total Orders for delivery boy
$queryTotal = "SELECT COUNT(*) as totalOrders FROM tblfinalorders WHERE deliveryid = ?";

$stmtTotal = $con->prepare($queryTotal);
$stmtTotal->bind_param("i", $deliveryId);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$rowTotal = $resultTotal->fetch_assoc();
$totalOrders = $rowTotal['totalOrders'];
$stmtTotal->close();

// Delivered Orders for delivery boy
$queryDelivered = "SELECT COUNT(*) as delivered FROM tblorderphotos WHERE deliveryboy_id = ? AND status = 'Delivered'";
$stmt = $con->prepare($queryDelivered);
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalDelivered = $row['delivered'];
$stmt->close();

// Pending Orders = Total Orders - Delivered
$totalPending = $totalOrders - $totalDelivered;

// Cancelled Orders for delivery boy
$queryCancelled = "SELECT COUNT(*) as cancelled FROM tblorderphotos WHERE deliveryboy_id = ? AND status = 'Cancelled'";
$stmt = $con->prepare($queryCancelled);
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalCancelled = $row['cancelled'];
$stmt->close();



$totalDelivered = 0;

$query = "SELECT COUNT(*) as totalDelivered FROM tblorderphotos WHERE deliveryboy_id = ? AND status = 'Delivered'";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $deliveryId); // Assuming $deliveryId is from session
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalDelivered = $row['totalDelivered'];
$stmt->close();


// Total Orders for delivery boy
$deliveryId = $_SESSION['deliveryboy_id']; // Must be set in session

// DEBUG: print delivery ID

// Prepare query
$queryTotal = "SELECT COUNT(*) as totalOrders FROM tblfinalorders WHERE deliveryid = ?";
$stmt = $con->prepare($queryTotal);

if (!$stmt) {
    die("Prepare failed: " . $con->error);
}

$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $row = $result->fetch_assoc();
    $totalOrders = $row['totalOrders'];
    //echo "Total Orders: " . $totalOrders;
} else {
    echo "Error fetching result: " . $stmt->error;
}

$stmt->close();
$stmt = $con->prepare($queryTotal);
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalOrders = $row['totalOrders'];
$stmt->close();

// Delivered Orders for delivery boy
$queryDelivered = "SELECT COUNT(*) as delivered FROM tblorderphotos WHERE deliveryboy_id = ? AND status = 'Delivered'";
$stmt = $con->prepare($queryDelivered);
$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalDelivered = $row['delivered'];
$stmt->close();

// Calculate Pending Orders

// Not Delivered (Pending Orders)
$queryPending = "SELECT COUNT(*) as notDelivered FROM tblorderphotos WHERE deliveryboy_id = ? AND status = 'NotDelivered'";
$stmt = $con->prepare($queryPending);

if (!$stmt) {
    die("Prepare failed: " . $con->error);
}

$stmt->bind_param("i", $deliveryId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $totalPending = $row['notDelivered'];
} else {
    $totalPending = 0; // fallback in case of no result
}

$stmt->close();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delivery Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            display: flex;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }

        .sidebar h4 {
            margin-bottom: 30px;
        }

        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }

        .sidebar a:hover {
            color: #fff;
        }

        /* Content area */
        .content-area {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h4 {
            font-weight: bold;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 12px;
            background: linear-gradient(to right, #ffffff, #f1f1f1);
            box-shadow: 0 0 12px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.1);
        }

        .card h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #555;
        }

        .card h3 {
            font-size: 2.2rem;
            font-weight: bold;
            color: #343a40;
        }

        .card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #ffc107;
        }

        @media(max-width: 768px) {
            .card h3 { font-size: 1.5rem; }
            .card h5 { font-size: 0.9rem; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>🚚 Delivery Menu</h4>
    <a href="#"><i class="fas fa-home me-2"></i> Dashboard</a>
    <a href="view-orders.php"><i class="fas fa-truck me-2"></i> Total Orders</a>
    <a href="Delivered-orders.php"><i class="fas fa-times-circle me-2"></i> Delivered Orders</a>
	    <a href="pending-orders.php"><i class="fas fa-clock me-2"></i> NotDelivered Orders</a>

    <a href="profile.php"><i class="fas fa-user me-2"></i> Profile</a>
    <a href="changepassword.php"><i class="fas fa-lock me-2"></i> Change Password</a>
    <a href="../index.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<!-- Content -->
<div class="container mt-5">
    <!-- Row 1: Heading Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-center shadow p-4">
                <h2>Welcome to Delivery Dashboard</h2>
                <p class="text-muted">Track your assigned orders and progress</p>
            </div>
        </div>
    </div>

    <!-- Row 2: Total, Delivered, Pending -->
    <div class="row mb-4">
        <!-- Total Orders -->
        <div class="col-md-4 mb-3">
            <a href="view-orders.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow p-4">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <h5>Total Orders</h5>
                    <h3><?= $totalOrders ?></h3>
                </div>
            </a>
        </div>

        <!-- Delivered Orders -->
        <div class="col-md-4 mb-3">
            <a href="delivered-orders.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow p-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <h5>Delivered</h5>
                    <h3><?= $totalDelivered ?></h3>
                </div>
            </a>
        </div>

        <!-- Pending Orders -->
        <div class="col-md-4 mb-3">
            <a href="pending-orders.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow p-4">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h5>NotDelivered</h5>
                    <h3><?= $totalPending ?></h3>
                </div>
            </a>
        </div>
    </div>

    <!-- Row 3: Cancelled Orders (Uncomment when needed) 
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <a href="cancelled-orders.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow p-4">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h5>Cancelled</h5>
                    <h3><?= $totalCancelled ?></h3>
                </div>
            </a>
        </div>
    </div>
</div>-->
        <!-- Cancelled Orders 
        <div class="col-md-3 mb-4">
            <a href="cancelled-orders.php" class="text-decoration-none text-dark">
                <div class="card text-center shadow p-4">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h5>Cancelled</h5>
                    <h3><?= $totalCancelled ?></h3>
                </div>
            </a>
        </div>-->
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
