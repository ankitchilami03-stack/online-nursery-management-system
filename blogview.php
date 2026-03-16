<?php
include('admin/includes/config.php');
error_reporting(0);

// Fetch blogs
$query = mysqli_query($con, "SELECT * FROM blog ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plant News & Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-card {
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            background: #fff;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .blog-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .blog-meta {
            font-size: 14px;
            color: #777;
            margin-bottom: 12px;
        }

        .blog-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .blog-content {
            font-size: 16px;
            line-height: 1.6;
        }

        body {
            background-color: #f2f2f2;
            padding-top: 30px;
        }

        .section-title {
            text-align: center;
            font-size: 30px;
            margin-bottom: 40px;
        }
    </style>
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/form.css" rel="stylesheet" type="text/css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="js/jquery1.min.js"></script>
<!-- start menu -->
<link href="css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
</head>
<body>
	<?php include_once('includes/BaseHeader.php');?>

<div class="container">
    <h2 class="section-title">🌿 Latest Plant News & Blogs</h2>

    <?php
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
    ?>
        <div class="blog-card">
            <div class="blog-title"><?php echo htmlentities($row['title']); ?></div>
            <div class="blog-meta">Posted on <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></div>
            <?php if (!empty($row['image'])) { ?>
                <img src="admin/blogimages/<?php echo htmlentities($row['image']); ?>" alt="Blog Image" class="blog-image">
            <?php } ?>
            <div class="blog-content"><?php echo nl2br(htmlentities($row['content'])); ?></div>
        </div>
    <?php
        }
    } else {
        echo "<p class='text-danger text-center'>No blog posts found.</p>";
    }
    ?>
</div>

</body>
</html>
