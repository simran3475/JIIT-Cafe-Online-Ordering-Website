<?php
  session_start();
  $con = mysqli_connect("localhost", "root", "admin", "test");
if (mysqli_connect_errno())
  {
  echo "Failed to connect to Database: " . mysqli_connect_error();
  }
  if (!isset($_SESSION['customer_email'])) {
    header("location: customer_login.php");
  }
  $user = $_SESSION['customer_email'];
  
  ?>
<?php
       // echo "alert('hi')";
        $items=$_COOKIE['my_cookie'];
        //echo $items;
        //explode(",", $my_string)
        $my_array = [];
        $getCustomerId = "SELECT customer_id FROM Customer WHERE customer_email='$user'";
        $runQueryCid = mysqli_query($con, $getCustomerId);
        $row = mysqli_fetch_assoc($runQueryCid);
        $customer_id = $row['customer_id'];
        
        $num=0;
        for ($i = 1; $i < strlen($items)-1; $i++) {
          if ($items[$i] != ",") { 
            $num = ($num*10)+$items[$i];
          }
          else{
            $my_array[] = $num; 
            $num=0;
          }
        } 
        $my_array[] = $num; 
        //echo var_dump($my_array);
      //print_r($items);
       //print_r($my_array);
        // get last order_id
        $queryGetLastOrderId = "SELECT  MAX(order_id) as myOrderId
        FROM OrderXCustomer";
        $runQueryLastOrderId = mysqli_query($con,$queryGetLastOrderId);
        $rowOrderId = mysqli_fetch_assoc($runQueryLastOrderId);
        $lastOrderId = $rowOrderId['myOrderId'];
         $newOrderId=$lastOrderId+1;
      //    echo $lastOrderId;
      //    echo "<br>";
      //    echo $customer_id;
       //$date = "select current_date()";
       // mysqli_query($con,$date);
        $query = "INSERT INTO `OrderXCustomer`  VALUES ('$newOrderId','$customer_id','2023-11-30')";
        mysqli_query($con,$query);
       
         //echo count($my_array);
        
        for ($j = 0; $j < count($my_array); $j++) { 
            //echo ".<br>$my_array[$j]";
            //  echo "<br>";
            $queryOrderXItems = "INSERT INTO OrderXItems(order_id,item_id,order_quantity,customer_id) VALUES('$newOrderId','$my_array[$j]','1','$customer_id')";
            mysqli_query($con,$queryOrderXItems); 
        } 

        echo "<script>alert('ordered successfully')</script>";
        header("location: grocery_stall.php?state=Success");
        header("location: Home.html?state=Success");
  ?>