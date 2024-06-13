<?php
    include('../connection.php');

    $patient_ID=$_GET['id'];

    $query = "EXEC patient_delete $patient_ID";
    $result = sqlsrv_query($conn, $query);

    header("Location: patients_browse.php");
    exit();
?>