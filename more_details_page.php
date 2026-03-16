<?php
session_start();
include('includes/config.php');
error_reporting(0);

// Show PHP errors during development (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['pid'])) {
    header('Location: index.php');
    exit();
}

$pid = intval($_GET['pid']);

$query = mysqli_query($con, "SELECT * FROM tblproducts WHERE ID='$pid'");
$row = mysqli_fetch_array($query);

if (!$row) {
    echo "Product not found.";
    exit();
}

$productName = $row['productName'];
$categoryName = $row['category']; // Already a string like 'Plant', 'Flower', etc.
$productWeight = $row['productweight'];
$productPrice = $row['productPrice'];
$productDescription = $row['productDescription'];
$productInstruction = $row['productInstruction'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product Info</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/form.css" rel="stylesheet" type="text/css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
<script src="js/jquery1.min.js"></script>
<link href="css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script src="js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
<link rel="stylesheet" href="css/etalage.css">
<script src="js/jquery.etalage.min.js"></script>
<script>
jQuery(document).ready(function($){
    $('#etalage').etalage({
        thumb_image_width: 360,
        thumb_image_height: 360,
        source_image_width: 900,
        source_image_height: 900,
        show_hint: true
    });
});
function showReviewForm() {
    document.getElementById("reviewForm").style.display = "block";
}
</script>
<style>
    .btn { background: #2ecc71; color: white; padding: 10px 20px; border: none; margin: 20px auto; display: block; border-radius: 6px; cursor: pointer; font-size: 16px; }
    .btn:hover { background: #27ae60; }
    #reviewForm { display: none; margin-top: 30px; }
    input[type="text"], select, textarea, input[type="file"] { width: 100%; padding: 10px; margin-top: 15px; border: 1px solid #ccc; border-radius: 6px; }
    textarea { height: 100px; }
    .review { background: #ecf0f1; padding: 20px; border-radius: 8px; margin-top: 20px; position: relative; }
    .stars { color: #f39c12; font-size: 18px; margin-top: 10px; }
    .review img { max-width: 150px; margin-top: 15px; border-radius: 5px; }
    .datetime { font-size: 12px; color: #7f8c8d; margin-top: 5px; }
    .bar-container { background-color: #eee; border-radius: 6px; height: 18px; width: 70%; display: inline-block; margin: 5px 0; }
    .bar-fill { background-color: #f1c40f; height: 100%; border-radius: 6px; }
    .bar-label { width: 60px; display: inline-block; }
</style>

</head>
<body>
<?php include_once('includes/header.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Product Details</h2>
    <table class="table table-bordered">
        <tr>
            <th>Product Name</th>
            <td><?php echo htmlspecialchars($productName); ?></td>
        </tr>
        <tr>
            <th>Category</th>
            <td><?php echo htmlspecialchars($categoryName); ?></td>
        </tr>
        <tr>
            <th>Weight</th>
            <td><?php echo htmlspecialchars($productWeight); ?> kg</td>
        </tr>
        <tr>
            <th>Price</th>
            <td>₹<?php echo number_format($productPrice, 2); ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?php echo nl2br(htmlspecialchars($productDescription)); ?></td>
        </tr>
        <tr>
            <th>Instructions</th>
            <td><?php echo nl2br(htmlspecialchars($productInstruction)); ?></td>
        </tr>
    </table>
    <div class="text-center">
        <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>
    </div>
</div>
</body>
</html>
