<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['submit']))
  {
    $name=$_POST['name'];
    $email=$_POST['email'];
    $message=$_POST['message'];
     
    $query=mysqli_query($con, "insert into tblcontact(Name,Email,Message) value('$name','$email','$message')");
    if ($query) {
   echo "<script>alert('Your message was sent successfully!.');</script>";
echo "<script>window.location.href ='contact.php'</script>";
  }
  else
    {
       echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }

  
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Zoo Animal Adoption</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Font Awesome Icons -->
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
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f2f7ff;
      color: #333;
    }

    h1.heading {
      text-align: center;
      padding: 40px 20px 10px;
      font-size: 36px;
      color: #2c3e50;
    }

    h1.heading span {
      color: #27ae60;
    }

    .zoo-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      padding: 30px 50px;
    }

    .zoo-box {
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      transition: 0.3s ease-in-out;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .zoo-box:hover {
      transform: translateY(-6px);
    }

    .zoo-box img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .zoo-box .content {
      padding: 20px;
    }

    .zoo-box h3 {
      font-size: 20px;
      color: #1c1c1c;
      margin-bottom: 8px;
    }

    .zoo-box p {
      font-size: 14px;
      margin-bottom: 6px;
      color: #555;
    }

    .zoo-box p i {
      margin-right: 6px;
      color: #27ae60;
    }

    .stars {
      margin-top: 10px;
      font-size: 16px;
      color: #f1c40f;
    }

    .btn {
      margin: 15px 0 10px;
      text-align: center;
      padding: 10px 15px;
      background: #27ae60;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      display: block;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #219150;
    }

    @media (max-width: 600px) {
      .zoo-container {
        padding: 20px;
      }

      h1.heading {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>
	<?php include_once('includes/header.php');?>

  <h1 class="heading">Farmers Make Order <span></span></h1>

  <div class="zoo-container">

    <!-- Box 1 -->
    <div class="zoo-box">
      <img src="https://th.bing.com/th/id/OSK.HEROoM7RmuW4EY6xbJveQeer5of_ocvd9v0VuvzmejbqC5I?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3" alt="Sugercane">
      <div class="content">
        <h3>Sugercane</h3>
        <p><i class="fas fa-map-marker-alt"></i>Sugercane</p>
        <p><i class="fas fa-paw"></i> Total: 12</p>
        <div class="stars">★★★★☆</div>
        <a href="productfilter.php?ID=1" class="btn">Order Sugercane</a>
      </div>
    </div>

    <!-- Box 2 -->
    <div class="zoo-box">
      <img src="https://th.bing.com/th/id/OIP.7n4FjH8CgNNAk4Zn1rZghwAAAA?r=0&o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3" alt="Flowers">
      <div class="content">
        <h3>Flowers</h3>
        <p><i class="fas fa-map-marker-alt"></i> Flowers</p>
        <p><i class="fas fa-paw"></i> Total: 6</p>
        <div class="stars">★★★★★</div>
        <a href="productfilter.php?ID=3" class="btn">Order Flowers</a>
      </div>
    </div>

    <!-- Box 3 -->
    <div class="zoo-box">
      <img src="https://www.millcreekgardens.com/wp-content/uploads/2020/02/Best-Utah-plant-nursery.jpg" alt="Tree">
      <div class="content">
        <h3>Tree</h3>
        <p><i class="fas fa-map-marker-alt"></i> Tree</p>
        <p><i class="fas fa-paw"></i> Total: 18</p>
        <div class="stars">★★★★☆</div>
        <a href="productfilter.php?ID=7" class="btn">Order Tree</a>
      </div>
    </div>

    <!-- Box 4 -->
    <div class="zoo-box">
      <img src="https://blog.nationwide.com/wp-content/uploads/2015/06/main-garden-2000.jpg" alt="Vegitable">
      <div class="content">
        <h3>Vegitable</h3>
        <p><i class="fas fa-map-marker-alt"></i>Vegitable</p>
        <p><i class="fas fa-paw"></i> Total: 5</p>
        <div class="stars">★★★☆☆</div>
        <a href="productfilter.php?ID=2" class="btn">Order Vegitable</a>
      </div>
    </div>

  </div>

</body>
</html>
