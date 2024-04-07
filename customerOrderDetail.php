<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  session_start();
  include("db.php");
  
  if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
    exit(); // Make sure to exit after a header redirect
  }
  
  $user = $_SESSION['customer_email'];
  ?>

  <meta charset="UTF-8">
  <link rel="stylesheet" href="Profile.css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="orderDetails.css" />
  <title>Order Details</title>
</head>

<body>

  <table id="order_details">
    <tr>
      <td><b>Item Name</b></td>
      <td class="spacespace">spacespace</td>
      <td><b>Quantity</b></td>
      <td class="spacespace">spacespace</td>
      <td><b>Price</b></td>
    

    <?php
    $getCustomerId = "SELECT customer_id FROM Customer WHERE customer_email=?";
    $stmtCid = mysqli_prepare($con, $getCustomerId);
    mysqli_stmt_bind_param($stmtCid, "s", $user);
    mysqli_stmt_execute($stmtCid);
    $resultCid = mysqli_stmt_get_result($stmtCid);
    $row = mysqli_fetch_assoc($resultCid);
    $customer_id = $row['customer_id'];
    
    if (isset($_COOKIE["order_id"])) {
      $order_id = $_COOKIE["order_id"];
      $getOrderDetails = "SELECT I.item_name as item_name, O.order_quantity as order_quantity, I.item_price as item_price FROM Items I, OrderXItems O WHERE O.item_id=I.item_id AND O.order_id=?";
      $stmtOrderDetails = mysqli_prepare($con, $getOrderDetails);
      mysqli_stmt_bind_param($stmtOrderDetails, "i", $order_id);
      mysqli_stmt_execute($stmtOrderDetails);
      $resultOrderDetails = mysqli_stmt_get_result($stmtOrderDetails);
    
      if (mysqli_num_rows($resultOrderDetails) > 0) {
        $i = 1;
        $total_price = 0;
        while ($row = mysqli_fetch_assoc($resultOrderDetails)) {
          $item_name = $row['item_name'];
          $item_price = $row['item_price'];
          $order_quantity = $row['order_quantity'];
    
          echo '
                <tr>
                    <td>' . $item_name . '</td>
                    <td class="spacespace">spacespace</td>
                    <td>' . $order_quantity . '</td>
                    <td class="spacespace">spacespace</td>
                    <td>' . ($item_price) * ($order_quantity) . '</td>
                </tr>
                ';
          $total_price += ($item_price) * ($order_quantity);
        }
    
        echo '
            <tr>
                <td><b>' . 'Total Price :   ' . '</b></td>
                <td class="spacespace">spacespace</td>
                <td class="spacespace">spacespace</td>
                <td class="spacespace">spacespace</td>
                <td><b>' . $total_price . '</b></td>
            </tr>
            ';
      } else {
        echo "No order details found for order_id: $order_id";
      }
    } else {
      echo "No order_id found in cookies.";
    }
    
    // Close prepared statements
    mysqli_stmt_close($stmtCid);
    mysqli_stmt_close($stmtOrderDetails);
    
    // Close database connection
    mysqli_close($con);
    ?>
  </table>
</body>
</html>




