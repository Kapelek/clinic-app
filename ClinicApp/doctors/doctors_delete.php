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
    $doctor_ID=$_GET['id'];

    $query = "EXEC doctor_delete $doctor_ID";
    $result = sqlsrv_query($conn, $query);

    header("Location: doctors_browse.php");
    exit();
?>