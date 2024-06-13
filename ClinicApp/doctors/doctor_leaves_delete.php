<?php
    include('../connection.php');
    session_start();
    if(!isset($_SESSION['account_ID'])){
        header("Location: ../login/login.php");
        exit();
    }else if($_SESSION['AP']==0){
        header("Location: ../main.php");
        exit();
    }
    $doctor_ID=$_GET['doctor_id'];
    $leave_ID=$_GET['leave_id'];

    $query = "EXEC leave_delete $leave_ID";
    $result = sqlsrv_query($conn, $query);

    header("Location: doctors_details_leaves.php?id=$doctor_ID");
    exit();
?>