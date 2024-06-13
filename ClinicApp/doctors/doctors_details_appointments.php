<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Appointments</title>
    <link rel="stylesheet" href="css/doctors_details_appointments.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php
        include('../connection.php');
        session_start();
        if(!isset($_SESSION['account_ID'])){
            header("Location: ../login/login.php");
            exit();
        }
        $doctor_ID=$_GET['id'];
        $query1 = "EXEC doctor_show_details $doctor_ID";
        $result1 = sqlsrv_query($conn, $query1);
        while($row = sqlsrv_fetch_array($result1)) {
            $doctor_name=$row['doctor_name'];
            $doctor_surname=$row['doctor_surname'];
        }
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <p>DOCTOR'S APPOINTMENTS - <?php echo $doctor_name," ",$doctor_surname ?></p>
            <span class="material-symbols-outlined" onclick="window.location.href='doctors_details.php?id=<?php echo $doctor_ID ?>'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <form action="#" id="filters"  autocomplete="off" METHOD="GET">
                <h2 id="order-label">Order: </h2>
                <input type="number" value="<?php echo $doctor_ID ?>" class="display-none" name="id">
                <select name="order" id="order" readonly onchange="updateRecords()">
                    <option value="latests">From The Latests</option>
                    <option value="oldests">From The Oldests</option>
                </select>
            </form>
        </div>
        <div id="appointment-history">
            <ul id="col-names-bar">
                <li class="col-name col-name-id">ID</li>
                <li class="col-name">DATE</li>
                <li class="col-name col-name-description">DESCRIPTION</li>
                <li class="col-name col-name-doc-id">PATIENT ID</li>
                <li class="col-name">PATIENT NAME</li>
                <li class="col-name">PATIENT SURNAME</li>
                <p id="appointments-count">APPOINTMENTS (<a id="num-rows"></a>)</p>
            </ul>
            <ul id="rows-place"></ul>
        </div>
    </div>
    <script src="js/doctors_details_appointments_get_records.js"></script>
</body>
</html>