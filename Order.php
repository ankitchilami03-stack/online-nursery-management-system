order page:<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];

    // Fetch product details
    $query = mysqli_query($con, "SELECT productName, category, ProductPrice FROM tblproducts WHERE ID = '$pid'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $catid = $row['category'];
        $productName = $row['productName'];
        $productPrice = $row['ProductPrice'];

        // Convert category ID to category name
        $categoryName = 'Unknown';
        switch ($catid) {
            case 1: $categoryName = "Plant"; break;
            case 2: $categoryName = "Flower"; break;
            case 3: $categoryName = "Seeds"; break;
            case 6: $categoryName = "Sugarcane"; break;
            case 7: $categoryName = "Vegetable"; break;
        }

        // Delivery days logic
        $deliveryDays = 10;
        if ($pid == 1) $deliveryDays = 25;
        elseif ($pid == 2) $deliveryDays = 15;
        elseif ($catid == 3) $deliveryDays = 2;
        elseif ($catid == 6) $deliveryDays = 20;
        elseif ($catid == 7) $deliveryDays = 15;

        $deliveryDate = date('Y-m-d', strtotime("+$deliveryDays days"));
    } else {
        echo "<script>alert('Product not found'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No product selected'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Plant Order Form</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f7fff7; padding: 20px; }
    .container { max-width: 800px; margin: auto; background: #fff; border: 5px solid green; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,128,0,0.1); }
    h2 { text-align: center; color: #2e7d32; margin-bottom: 30px; }
    .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
    .form-group { flex: 1; min-width: 200px; display: flex; flex-direction: column; }
    label { font-weight: 600; margin-bottom: 5px; }
    input, textarea { padding: 10px; border-radius: 5px; border: 1px solid #ccc; background: #e6ffe6; font-size: 14px; }
    textarea { resize: vertical; }
    .submit-btn { width: 100%; background: #2e7d32; color: white; border: none; padding: 15px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 20px; }
    .submit-btn:hover { background: #256d27; }
    @media (max-width: 600px) { .form-row { flex-direction: column; } }
  </style>
</head>
<body>

<div class="container">
  <h2>Plant Order Form</h2>
  <form action="billingform.php" method="POST">

    <!-- Hidden values -->
    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
    <input type="hidden" name="catid" value="<?php echo $catid; ?>">
    <input type="hidden" name="delivery" value="<?php echo $deliveryDate; ?>">
    <input type="hidden" name="productPrice" id="productPrice" value="<?php echo $productPrice; ?>">

    <!-- Product Info -->
    <div class="form-group">
      <label>Product Name</label>
      <input type="text" name="pname" value="<?php echo htmlspecialchars($productName); ?>" readonly>
    </div>

    <div class="form-group">
      <label>Category</label>
      <input type="text" name="catname" value="<?php echo htmlspecialchars($categoryName); ?>" readonly>
    </div>

    <div class="form-group">
      <label>Each Plant Rate (₹)</label>
      <input type="text" value="<?php echo $productPrice; ?>" readonly>
    </div>

    <!-- Personal Details -->
    <div class="form-row">
      <div class="form-group">
        <label>First Name</label>
        <input type="text" name="fname" required>
      </div>
      <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="lname" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email">
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="tel" name="phone" pattern="[0-9]{10}" required>
      </div>
    </div>

    <!-- Address -->
    <div class="form-group">
      <label>Street Address</label>
      <input type="text" name="address1" required>
    </div>

    <div class="form-group">
      <label>Street Address Line 2</label>
      <input type="text" name="address2">
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>City</label>
        <input type="text" name="city" required>
      </div>
      <div class="form-group">
        <label>State / Province</label>
        <input type="text" name="state" required>
      </div>
    </div>

    <div class="form-group">
      <label>Postal / Zip Code</label>
      <input type="text" name="zip" required>
    </div>

    <!-- Quantity and Total Price -->
    <div class="form-row">
      <div class="form-group">
        <label>Quantity</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
      </div>
      <div class="form-group">
        <label>Total Price (₹)</label>
        <input type="text" id="totalPrice" name="totalPrice" readonly>
      </div>
    </div>

    <div class="form-group">
      <label>Special Notes</label>
      <textarea name="note" rows="3"></textarea>
    </div>

    <button type="submit" class="submit-btn">Place Order</button>
  </form>
</div>

<script>
  const price = parseFloat(document.getElementById('productPrice').value);
  const quantityInput = document.getElementById('quantity');
  const totalInput = document.getElementById('totalPrice');

  function updateTotal() {
    const qty = parseInt(quantityInput.value) || 0;
    totalInput.value = (price * qty).toFixed(2);
  }

  quantityInput.addEventListener('input', updateTotal);
</script>

</body>
</html>
