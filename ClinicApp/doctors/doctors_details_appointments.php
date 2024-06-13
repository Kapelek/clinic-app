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

        $doctor_ID=$_GET['id'];

        $query1 = "EXEC doctor_show_details $doctor_ID";

        if(!isset($_GET['order']) || $_GET['order']=="latests"){
            $query2 = "SELECT * FROM appointments_print WHERE doctor_ID=$doctor_ID ORDER BY appointment_date DESC";
        }else{
            $query2 = "SELECT * FROM appointments_print WHERE doctor_ID=$doctor_ID ORDER BY appointment_date ASC";
        }

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);

        while($row = sqlsrv_fetch_array($result1)) {
            $doctor_name=$row['doctor_name'];
            $doctor_surname=$row['doctor_surname'];
        }

        $numRows=0;
        $data = array();
        while($row = sqlsrv_fetch_array($result2)) {
            $data[] = $row;
            $numRows++;
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
                <select name="order" id="order" readonly>
                    <option value="latests" <?php echo isset($_GET['order']) && $_GET['order'] == 'latests' ? 'selected' : ''; ?>>From The Latests</option>
                    <option value="oldests" <?php echo isset($_GET['order']) && $_GET['order'] == 'oldests' ? 'selected' : ''; ?>>From The Oldests</option>
                </select>
                <button type="submit" id="submit-btn"><span class="material-symbols-outlined">search</span></button>
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
                <p id="appointments-count">APPOINTMENTS (<?php echo $numRows ?>)</p>
            </ul>
            <ul id="rows-place">
                <?php
                    foreach($data as $row) {
                        $appointment_ID = $row['appointment_ID'];
                        $appointment_date = $row['appointment_date'];
                        $formatted_patient_appointment_date = $appointment_date->format('d.m.Y');
                        $appointment_description = $row['appointment_description'];
                        $patient_ID=$row['patient_ID'];
                        $patient_name=$row['patient_name'];
                        $patient_surname=$row['patient_surname'];
                ?>
                <li class="record">
                    <div class="record-data-places">
                        <div class="record-col record-col-id"><?php echo $appointment_ID ?></div>
                        <div class="record-col"><?php echo $formatted_patient_appointment_date ?></div>
                        <div class="record-col record-col-description"><?php echo $appointment_description ?></div>
                        <div class="record-col record-col-doc-id"><?php echo $patient_ID ?></div>
                        <div class="record-col"><?php echo $patient_name ?></div>
                        <div class="record-col"><?php echo $patient_surname ?></div>
                    </div>
                    <div class="record-btns-place">
                        <a href="" class="record-btn">SHOW DETAILS</a>
                    </div>
                </li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>