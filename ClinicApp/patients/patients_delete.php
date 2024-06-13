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
    
    $patient_ID=$_GET['id'];

    $query = "EXEC patient_delete $patient_ID";
    $result = sqlsrv_query($conn, $query);

    header("Location: patients_browse.php");
    exit();
?>