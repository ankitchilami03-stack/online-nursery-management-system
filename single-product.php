<?php
session_start();
include('includes/config.php');
$connect = new PDO("mysql:host=localhost;dbname=nmsdb", "root", "");

$pid = $_GET['pid'];

// Handle review form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating_data'])) {
    $imageName = "";

    if (!empty($_FILES["review_image"]["name"])) {
        $folder = "review_images/";
        if (!is_dir($folder)) {
            mkdir($folder);
        }

        $imageName = time() . "_" . basename($_FILES["review_image"]["name"]);
        move_uploaded_file($_FILES["review_image"]["tmp_name"], $folder . $imageName);
    }

    $data = [
        ':product_id'   => $_POST["product_id"],
        ':user_name'    => $_POST["user_name"],
        ':user_rating'  => $_POST["rating_data"],
        ':user_review'  => $_POST["user_review"],
        ':review_image' => $imageName,
        ':datetime'     => time()
    ];

    $sql = "INSERT INTO review_table (product_id, user_name, user_rating, user_review, review_image, datetime)
            VALUES (:product_id, :user_name, :user_rating, :user_review, :review_image, :datetime)";
    $stmt = $connect->prepare($sql);
    $stmt->execute($data);
    echo "<script>alert('Review Submitted Successfully!');</script>";
}

// Rating breakdown
$ratings = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$total_reviews = 0;
$stmt = $connect->prepare("SELECT user_rating FROM review_table WHERE product_id = :pid");
$stmt->execute([':pid' => $pid]);

foreach ($stmt as $r) {
    $ratings[$r['user_rating']]++;
    $total_reviews++;
}

if (isset($_POST['submit'])) {
    $pid = $_POST['pid'];
    $userid = $_SESSION['nmsuid'];
    $query = mysqli_query($con, "INSERT INTO orders(UserId, PId) VALUES('$userid','$pid')");
    if ($query) {
        echo "<script>alert('Product has been added to the cart');</script>";
        echo "<script>document.location ='cart.php';</script>";
    } else {
        echo "<script>alert('Something went wrong.');</script>";
    }
}

if (isset($_POST['wsubmit'])) {
    $wpid = $_POST['wpid'];
    $userid = $_SESSION['nmsuid'];
    $query = mysqli_query($con, "INSERT INTO wishlist(UserId, ProductId) VALUES('$userid','$wpid')");
    if ($query) {
        echo "<script>alert('Product has been added to the wishlist');</script>";
        echo "<script>document.location ='wishlist.php';</script>";
    } else {
        echo "<script>alert('Something went wrong.');</script>";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Nursery Management System | Single Product Details</title>
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
<div class="mens">
    <div class="main">
        <div class="wrap">
            <ul class="breadcrumb breadcrumb__t"><a class="home" href="index.php">Home</a> / Single Product Details</ul>
            <?php
            $ret = mysqli_query($con, "SELECT * FROM tblproducts JOIN tblcategory ON tblcategory.ID = tblproducts.category WHERE tblproducts.ID = '$pid'");
            while ($row = mysqli_fetch_array($ret)) {
            ?>
            <div class="cont span_2_of_3">
                <div class="grid images_3_of_2">
                    <ul id="etalage">
                        <li><img class="etalage_thumb_image" src="admin/productimages/<?php echo $row['productImage1']; ?>" />
                            <img class="etalage_source_image" src="admin/productimages/<?php echo $row['productImage1']; ?>" /></li>
                        <li><img class="etalage_thumb_image" src="admin/productimages/<?php echo $row['productImage2']; ?>" />
                            <img class="etalage_source_image" src="admin/productimages/<?php echo $row['productImage2']; ?>" /></li>
                        <li><img class="etalage_thumb_image" src="admin/productimages/<?php echo $row['productImage3']; ?>" />
                            <img class="etalage_source_image" src="admin/productimages/<?php echo $row['productImage3']; ?>" /></li>
                    </ul>
                </div>
                <div class="desc1 span_3_of_2">
                    <h3 class="m_3"><?php echo $row['productName']; ?></h3>
					<span class="m_link">Product Amount.</span>

                    <p class="m_5">₹<?php echo $row['productPrice']; ?></p>
                    <span class="m_link">Product info.</span>
                    <p class="m_text2"><b>Category:</b> <?php echo $row['CategoryName']; ?></p>
                    <p class="m_text2"><b>Product Weight:</b> <?php echo $row['productweight']; ?></p>
                    <p class="m_text2"><b>Shipping Charge:</b> ₹<?php echo $row['shippingCharge']; ?></p>
                </div>
               <!-- <div style="margin-top:40%;" align="center">
<?php if($_SESSION['nmsuid']==""){?>
<a href="login.php"><button  class="grey">Add to cart</button></a>
<?php } else {?>
<form method="post"> 	
 <input type="hidden" name="pid" value="<?php echo $row['ID'];?>">   
<button type="submit" name="submit" class="grey">Add to Cart</button>
</form> <?php } ?>-->
                    <?php if ($_SESSION['nmsuid'] == "") { ?>
                        <a href="login.php"><button class="grey">Wishlist</button></a>
                    <?php } else { ?>
                        <form method="post">
                            <input type="hidden" name="wpid" value="<?php echo $row['ID']; ?>">
                            <button type="submit" name="wsubmit" class="grey">Wishlist</button>
                        </form>
                    <?php } ?>
					<div style="margin-top: 20px;">
    <form method="get" action="more_details_page.php">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
        <button type="submit" class="grey">More Details</button>
    </form>
</div>

                </div>
                <div class="clear"></div>
            </div>
            <?php } ?>

            <!-- ========== REVIEW SECTION START ========== -->
        <div class="container" style="width:100%; max-width:900px; margin-left:400px;">
    <h2 style="text-align:center; font-weight:bold; color:#2c3e50;">Customer Reviews</h2>
    <button class="btn" onclick="showReviewForm()">Write a Review</button>

    <div id="reviewForm" class="shadow" style="background:#fff; padding:30px; border:1px solid #ccc; border-radius:8px;">
        <h3 style="text-align:center;">Write Your Review</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="product_id" value="<?php echo $pid; ?>" readonly>
            <input type="text" name="user_name" placeholder="Your Name" required>
            <label><strong>Rating:</strong></label>
            <select name="rating_data" required>
                <option value="">Select Rating</option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★☆</option>
                <option value="3">★★★☆☆</option>
                <option value="2">★★☆☆☆</option>
                <option value="1">★☆☆☆☆</option>
            </select>
            <textarea name="user_review" placeholder="Write your review..." required></textarea>
            <label><strong>Upload Photo (optional):</strong></label>
            <input type="file" name="review_image" accept="image/*">
            <button type="submit" class="btn">Submit Review</button>
        </form>
    </div>

    <h3 style="margin-top:40px; color:#34495e;">Rating Breakdown</h3>
    <?php foreach ($ratings as $star => $count): 
        $percent = $total_reviews ? ($count / $total_reviews) * 100 : 0;
    ?>
        <div style="margin-bottom: 10px;">
            <span class="bar-label"><?php echo $star; ?> Star</span>
            <div class="bar-container">
                <div class="bar-fill" style="width: <?php echo $percent; ?>%;"></div>
            </div>
            <span style="margin-left:10px; color:#555;">(<?php echo $count; ?>)</span>
        </div>
    <?php endforeach; ?>

    <h3 style="margin-top:40px; color:#34495e;">All Reviews</h3>
    <?php
    $stmt = $connect->prepare("SELECT * FROM review_table WHERE product_id = :pid ORDER BY review_id DESC");
    $stmt->execute([':pid' => $pid]);
    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $row) {
            echo "<div class='review shadow-sm' style='background:#fdfdfd; border-left:5px solid #3498db;'>";
            echo "<strong style='font-size:18px; color:#2c3e50;'>" . htmlspecialchars($row['user_name']) . "</strong><br>";
            echo "<div class='stars'>" . str_repeat("★", $row['user_rating']) . str_repeat("☆", 5 - $row['user_rating']) . "</div>";
            echo "<p style='color:#444;'>" . nl2br(htmlspecialchars($row['user_review'])) . "</p>";
            if (!empty($row['review_image'])) {
                echo "<img src='review_images/" . htmlspecialchars($row['review_image']) . "' alt='Review Image'>";
            }
            echo "<div class='datetime'>Posted on " . date('d M Y, h:i A', $row['datetime']) . "</div>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center; font-style:italic; color:#999;'>No reviews yet.</p>";
    }
    ?>
</div>

            <!-- ========== REVIEW SECTION END ========== -->

            <div class="rsingle span_1_of_single">
                <h5 class="m_1">Categories</h5>
                <ul class="kids">
                    <?php
                    $ret = mysqli_query($con, "SELECT * FROM tblcategory");
                    while ($row = mysqli_fetch_array($ret)) {
                    ?>
                    <li><a href="products.php?cid=<?php echo $row['ID']; ?>&catname=<?php echo $row['CategoryName']; ?>"><?php echo $row['CategoryName']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>
</body>
</html>
