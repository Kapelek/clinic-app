<?php
    include('../connection.php');

    $doctor_ID=$_GET['id'];

    $query = "EXEC doctor_delete $doctor_ID";
    $result = sqlsrv_query($conn, $query);

    header("Location: doctors_browse.php");
    exit();
?>