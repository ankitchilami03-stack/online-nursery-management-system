<?php
session_start();
include_once 'includes/config.php';
?>
<script language="javascript" type="text/javascript">
function f2() {
    window.close();
}
function f3() {
    window.print(); 
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Nursery Management System - Track Order</title>
</head>
<body>

<div style="margin-left:50px;">
<?php  
$orderid = intval($_GET['oid']);
$ret = mysqli_query($con, "SELECT tbltracking.OrderCanclledByUser, tbltracking.remark, tbltracking.status as bstatus, tbltracking.StatusDate FROM tbltracking WHERE tbltracking.OrderId = '$orderid'");
$cnt = 1;
?>
<table border="1" cellpadding="10" style="border-collapse: collapse; border-spacing:0; width: 100%; text-align: center;">
  <tr align="center">
    <th colspan="4">Tracking History of #<?php echo $orderid; ?></th> 
  </tr>
  <tr>
    <th>#</th>
    <th>Remark</th>
    <th>Status</th>
    <th>Time</th>
  </tr>
<?php  
while ($row = mysqli_fetch_array($ret)) { 
  $cancelledby = $row['OrderCanclledByUser'];
?>
<tr>
  <td><?php echo $cnt; ?></td>
  <td><?php echo $row['remark']; ?></td> 
  <td>
    <?php  
      echo $row['bstatus']; 
      if ($cancelledby == 1) {
          echo " (by user)";
      } else {
          echo " (by shop)";
      }
    ?>
  </td> 
  <td><?php echo $row['StatusDate']; ?></td> 
</tr>
<?php $cnt++; } ?>

<?php
// ✅ Check delivery status and created_at from tblorderphotos
$statusCheck = mysqli_query($con, "SELECT status, created_at FROM tblorderphotos WHERE orderid = '$orderid' LIMIT 1");
$deliveryRow = mysqli_fetch_assoc($statusCheck);

if ($deliveryRow && $deliveryRow['status'] === 'Delivered') {
?>
<tr>
  <td colspan="4" style="color: green; font-weight: bold;">
    Final Status: Delivered <br>
    Delivery Date: <?php echo date("d-m-Y", strtotime($deliveryRow['created_at'])); ?>
  </td>
</tr>
<?php 
} elseif ($deliveryRow && $deliveryRow['status'] === 'NotDelivered') {
?>
<tr>
  <td colspan="4" style="color: red; font-weight: bold;">
    Final Status: Not Delivered <br>
    Attempt Date: <?php echo date("d-m-Y", strtotime($deliveryRow['created_at'])); ?>
  </td>
</tr>
<?php } ?>
</table>

<p>
  <input name="Submit2" type="submit" class="txtbox4" value="Close" onClick="return f2();" style="cursor: pointer;" />
  <input name="Submit2" type="submit" class="txtbox4" value="Print" onClick="return f3();" style="cursor: pointer;" />
</p>
</div>

</body>
</html>
