<?php 
    session_start();
    include("db.php"); 
    $c_name = $_POST['c_name'];
    $c_email = $_POST['c_email'];
    $c_pass = $_POST['c_pass'];	
    $query="USE test";
	$run_query=mysqli_query($con,$query);

    $queryGetLastcustId = "SELECT  MAX(customer_id) as newcustomer
        FROM Customer";
        $runQueryLastcustId = mysqli_query($con,$queryGetLastcustId);
        $rowcustId = mysqli_fetch_assoc($runQueryLastcustId);
        $lastcustId = $rowcustId['newcustomer'];
         $newcustId=$lastcustId+1;
    $insert_c = "INSERT into Customer VALUES ('$c_email','$c_pass','$c_name',0,'$newcustId')";

    $run_c = mysqli_query($con, $insert_c); 

    if($run_c){
    $_SESSION['customer_email']=$c_email; 
    echo "<script>alert('Account has been created successfully')</script>";
    //echo "<script>window.open('Home.html')</script>";
    header("location: Home.html");
    }
    else{
        echo "<script>window.alert('Not able to insert')</script>";
        echo "<script>window.open('Login.html')</script>";
        header("location: Login.html");
    }