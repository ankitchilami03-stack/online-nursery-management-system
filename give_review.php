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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Review</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f7fa;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
            color: #2c3e50;
        }
        .btn {
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            margin: 20px auto;
            display: block;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #27ae60;
        }
        #reviewForm {
            display: none;
            margin-top: 30px;
        }
        input[type="text"], select, textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        textarea {
            height: 100px;
        }
        .review {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            position: relative;
        }
        .stars {
            color: #f39c12;
            font-size: 18px;
            margin-top: 10px;
        }
        .review img {
            max-width: 150px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .datetime {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .bar-container {
            background-color: #eee;
            border-radius: 6px;
            height: 18px;
            width: 70%;
            display: inline-block;
            margin: 5px 0;
        }
        .bar-fill {
            background-color: #f1c40f;
            height: 100%;
            border-radius: 6px;
        }
        .bar-label {
            width: 60px;
            display: inline-block;
        }
    </style>
    <script>
        function showReviewForm() {
            document.getElementById("reviewForm").style.display = "block";
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Product Reviews</h2>
    <p style="text-align:center; color:#555;">Product ID: <?php echo $pid; ?></p>
    <button class="btn" onclick="showReviewForm()">Write a Review</button>

    <!-- Review Form -->
    <div id="reviewForm">
        <h3>Write Your Review</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="product_id" value="<?php echo $pid; ?>" readonly>
            <input type="text" name="user_name" placeholder="Your Name" required>

            <label>Rating:</label>
            <select name="rating_data" required>
                <option value="">Select Rating</option>
                <option value="5">★★★★★</option>
                <option value="4">★★★★☆</option>
                <option value="3">★★★☆☆</option>
                <option value="2">★★☆☆☆</option>
                <option value="1">★☆☆☆☆</option>
            </select>

            <textarea name="user_review" placeholder="Write your review..." required></textarea>

            <label>Upload Photo (optional):</label>
            <input type="file" name="review_image" accept="image/*">

            <button type="submit" class="btn">Submit Review</button>
        </form>
    </div>

    <!-- Graph Section -->
    <h3 style="margin-top:40px">Rating Breakdown</h3>
    <?php foreach ($ratings as $star => $count): 
        $percent = $total_reviews ? ($count / $total_reviews) * 100 : 0;
    ?>
        <div>
            <span class="bar-label"><?php echo $star; ?> Star</span>
            <div class="bar-container">
                <div class="bar-fill" style="width: <?php echo $percent; ?>%;"></div>
            </div>
            <span>(<?php echo $count; ?>)</span>
        </div>
    <?php endforeach; ?>

    <!-- Review Display -->
    <h3>Customer Reviews</h3>
    <?php
    $stmt = $connect->prepare("SELECT * FROM review_table WHERE product_id = :pid ORDER BY review_id DESC");
    $stmt->execute([':pid' => $pid]);

    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $row) {
            echo "<div class='review'>";
            echo "<strong>" . htmlspecialchars($row['user_name']) . "</strong><br>";
            echo "<div class='stars'>" . str_repeat("★", $row['user_rating']) . str_repeat("☆", 5 - $row['user_rating']) . "</div>";
            echo "<p>" . nl2br(htmlspecialchars($row['user_review'])) . "</p>";
            if (!empty($row['review_image'])) {
                echo "<img src='review_images/" . htmlspecialchars($row['review_image']) . "' alt='Review Image'>";
            }
            echo "<div class='datetime'>Posted on " . date('d M Y, h:i A', $row['datetime']) . "</div>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>No reviews yet.</p>";
    }
    ?>
</div>
</body>
</html>