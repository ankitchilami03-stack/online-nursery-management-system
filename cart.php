<?php
session_start();
error_reporting(0);
include_once('includes/config.php');
if (strlen($_SESSION['nmsuid'] == 0)) {
    header('location:logout.php');
} else {
// Delete product from cart
if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM orders WHERE id='$rid'");
    echo "<script>alert('Data deleted');</script>"; 
    echo "<script>window.location.href = 'cart.php'</script>";
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Nursery Management System | Cart</title>
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
<div class="register_account">
    <div class="wrap">
        <table border="2" class="table table-responsive">
            <tr style="font-size: 20px;color: blue; border-width: 3px;">
                <th>Image</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Shipping</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Buy Now</th>
                <th>Delete</th>
            </tr>
            <?php 
            $userid = $_SESSION['nmsuid'];
            $query = mysqli_query($con, "SELECT tblproducts.ID, tblproducts.productName, tblproducts.shippingCharge, tblproducts.productPrice, tblproducts.productImage1, orders.id as oid FROM orders JOIN tblproducts ON tblproducts.ID = orders.PId WHERE orders.UserId='$userid' AND orders.IsOrderPlaced IS NULL");
            $num = mysqli_num_rows($query);
            if ($num > 0) {
                while ($row = mysqli_fetch_array($query)) {
                    $price = $row['productPrice'];
                    $shipping = $row['shippingCharge'];
                    $total = $price + $shipping;
            ?>
            <tr>
                <td width="300"><img src="admin/productimages/<?php echo $row['productImage1'];?>" height="150" width="200" alt=""></td>
                <td><?php echo $row['productName']; ?></td>
                <td><?php echo $price; ?></td>
                <td><?php echo $shipping; ?></td>
                <td>1</td>
                <td><?php echo $total; ?></td>
                <td>
                    <form method="post" action="checkout.php">
                        <input type="hidden" name="orderid" value="<?php echo $row['oid']; ?>">
                        <input type="submit" name="buynow" value="Buy Now" class="button">
                    </form>
                </td>
                <td>
                    <a href="cart.php?delid=<?php echo $row['oid']; ?>" onclick="return confirm('Do you really want to Delete ?');">Delete</a>
                </td>
            </tr>
            <?php } } else { ?>
            <tr>
                <td colspan="8" style="text-align:center;color:red;font-size:20px;">Cart is empty</td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>
</body>
</html>
<?php } ?>
