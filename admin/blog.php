<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit();
}

$msg = "";
$error = "";

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $content = mysqli_real_escape_string($con, $_POST['content']);

    // Image handling
    $imgname = $_FILES["image"]["name"];
    $imgtmp = $_FILES["image"]["tmp_name"];

    // Rename and set path
    $imgext = pathinfo($imgname, PATHINFO_EXTENSION);
    $imgnewname = uniqid("blog_", true) . '.' . $imgext;
    $imgpath = "blogimages/" . $imgnewname;

    if (move_uploaded_file($imgtmp, $imgpath)) {
        $query = mysqli_query($con, "INSERT INTO blog (title, content, image) VALUES ('$title', '$content', '$imgnewname')");

        if ($query) {
            $msg = "✅ Blog posted successfully!";
        } else {
            $error = "❌ Failed to save blog to database.";
        }
    } else {
        $error = "❌ Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin | Add Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link href="css/styles.css" rel="stylesheet" />
        <script src="js/all.min.js" crossorigin="anonymous"></script>
        <script src="js/jquery-3.5.1.min.js"></script>

    <style>
        .blog-form {
            max-width: 700px;
            margin: 30px auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="sb-nav-fixed">
<?php include_once('includes/header.php'); ?>
<div id="layoutSidenav">
    <?php include_once('includes/sidebar.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Add Blog Post</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Blog</li>
                </ol>

                <div class="blog-form">
                    <h2 class="text-center">🪴 New Blog Post</h2>

                    <?php if ($msg): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Blog Title</label>
                            <input type="text" id="title" name="title" class="form-control" required />
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Blog Content</label>
                            <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Blog Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*" required />
                        </div>

                        <button type="submit" name="submit" class="btn btn-success w-100">📢 Post Blog</button>
                    </form>
                </div>
            </div>
        </main>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
