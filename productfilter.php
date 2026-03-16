<?php
session_start();
include('includes/config.php');

$cat = isset($_GET['ID']) ? $_GET['ID'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlentities($cat); ?> Products</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='http://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="js/jquery1.min.js"></script>
<!-- start menu -->
<link href="css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
<script src="js/jquery.easydropdown.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #fff;
      margin: 0;
      padding: 40px;
    }

    h1 {
      text-transform: uppercase;
      color: #333;
      margin-bottom: 30px;
      font-size: 28px;
    }

    .container {
      display: flex;
      gap: 30px;
    }

    .grid {
      flex: 3;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 25px;
    }

    .card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      display: flex;
      flex-direction: column;
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-content {
      padding: 15px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .card-content h3 {
      font-size: 16px;
      margin: 10px 0;
      color: #444;
      min-height: 45px;
    }

    .card-content p {
      color: #27ae60;
      font-weight: bold;
      margin: 5px 0 10px;
    }

    .card-content .grey {
      background-color: #333;
      color: white;
      border: none;
      padding: 10px;
      font-size: 13px;
      cursor: pointer;
      width: 100%;
      margin-bottom: 10px;
    }

    .card-content .grey:hover {
      background-color: #555;
    }

    .sidebar {
      flex: 1;
    }

    .sidebar h3 {
      font-size: 16px;
      text-transform: uppercase;
      padding: 10px;
      background: #f5f5f5;
      border: 1px solid #ddd;
      margin-bottom: 10px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar ul li {
      padding: 10px 0;
    }

    .sidebar ul li a {
      text-decoration: none;
      color: #444;
    }

    .sidebar ul li a:hover {
      color: #27ae60;
    }

    .no-products {
      color: red;
      text-align: center;
      font-size: 18px;
      margin-top: 50px;
    }

    @media(max-width: 768px) {
      .container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
<?php include_once('includes/header.php');?>

<h1><?php ($cat); ?></h1>

<div class="container">
  <div class="grid">
    <?php
    $ret = mysqli_query($con, "SELECT * FROM tblproducts WHERE category = '$cat'");
    $count = mysqli_num_rows($ret);
    if ($count > 0) {
      while ($row = mysqli_fetch_array($ret)) {
    ?>
      <div class="card">
      <a href="single-product.php?pid=<?php echo $row['ID']; ?>">
      <img src="admin/productimages/<?php echo htmlentities($row['productImage1']); ?>" alt="">
</a>
	  <div class="card-content">
          <h3><?php echo htmlentities($row['productName']); ?></h3>
          <p>$<?php echo htmlentities($row['productPrice']); ?></p>
          <?php if ($_SESSION['nmsuid'] == "") { ?>
            <a href="login.php"><button class="grey">Add to Cart</button></a>
            <a href="login.php"><button class="grey">Wishlist</button></a>
          <?php } else { ?>
            <form method="post">
              <input type="hidden" name="pid" value="<?php echo $row['ID']; ?>">
              <button type="submit" name="submit" class="grey">Add to Cart</button>
            </form>
            <form method="post">
              <input type="hidden" name="wpid" value="<?php echo $row['ID']; ?>">
              <button type="submit" name="wsubmit" class="grey">Wishlist</button>
            </form>
          <?php } ?>
        </div>
      </div>
    <?php } } else {
      echo "<p class='no-products'>No products found in $cat</p>";
    } ?>
  </div>

  <div class="sidebar">
    <h3>Categories</h3>
    <ul>
      <?php
      $ret = mysqli_query($con, "SELECT * FROM tblcategory");
      while ($row = mysqli_fetch_array($ret)) {
      ?>
        <li>
          <a href="productfilter.php?ID=<?php echo $row['CategoryName']; ?>"><?php echo $row['CategoryName']; ?></a>
        </li>
      <?php } ?>
    </ul>
  </div>
</div>

</body>
</html>
