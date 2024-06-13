<?php
    error_reporting(0);
    include('../connection.php');

    $doctor_ID=$_GET['doctor_id'];
    $presence_ID=$_GET['presence_id'];

    $query = "EXEC presence_delete $presence_ID";
    $result = sqlsrv_query($conn, $query);

    header("Location: doctors_details_presence.php?id=$doctor_ID");
    exit();
?>