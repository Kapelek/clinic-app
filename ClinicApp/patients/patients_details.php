<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient's Details</title>
    <link rel="stylesheet" href="css/patients_details.css">
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
        $patient_ID=$_GET['id'];

        $query1 = "EXEC patient_show_details $patient_ID";
        $query2 = "SELECT * FROM appointments_print WHERE patient_id=$patient_ID ORDER BY appointment_date desc";

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);

        while($row = sqlsrv_fetch_array($result1)){
            $patient_name = $row['patient_name'];
            $patient_surname = $row['patient_surname'];
            $patient_date_of_birth = $row['patient_date_of_birth'];
            $formatted_patient_date_of_birth = $patient_date_of_birth->format('d.m.Y');
            $phone = $row['patient_phone'];
            $patient_phone = $row['patient_phone'];
            $patient_address= $row['patient_address'];
            $patient_city = $row['patient_city'];
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
            <p>PATIENT'S DETAILS - <?php echo $patient_name," ",$patient_surname?></p>
            <span class="material-symbols-outlined" onclick="window.location.href='patients_browse.php'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <a href="#" class="container-top-btn">NEW APPOINTMENT</a>
            <a href="patients_edit.php?id=<?php echo $patient_ID ?>" class="container-top-btn">EDIT PATIENT</a>
        </div>
        <div id="informations">
            <div class="info-line"><span class="data-name">ID:</span> <?php echo $patient_ID ?></div>
            <div class="info-line"><span class="data-name">NAME:</span> <?php echo $patient_name ?></div>
            <div class="info-line"><span class="data-name">SURNAME:</span> <?php echo $patient_surname ?></div>
            <div class="info-line"><span class="data-name">DATE OF BIRTH:</span> <?php echo $formatted_patient_date_of_birth ?></div>
            <div class="info-line"><span class="data-name">PHONE:</span> <?php echo $patient_phone ?></div>
            <div class="info-line"><span class="data-name">ADDRESS:</span> <?php echo $patient_city,", ",$patient_address ?></div>
        </div>
        <div id="appointment-history">
            <div id="appointment-history-title-bar">APPOINTMENTS HISTORY</div>
            <ul id="col-names-bar">
                <li class="col-name col-name-id">ID</li>
                <li class="col-name">DATE</li>
                <li class="col-name col-name-description">DESCRIPTION</li>
                <li class="col-name col-name-doc-id">DOCTOR ID</li>
                <li class="col-name">DOCTOR NAME</li>
                <li class="col-name">DOCTOR SURNAME</li>
                <p id="appointments-count">APPOINTMENTS (<?php echo $numRows ?>)</p>
            </ul>
            <ul id="rows-place">
                <?php
                    foreach($data as $row) {
                        $appointment_ID = $row['appointment_ID'];
                        $appointment_date = $row['appointment_date'];
                        $formatted_patient_appointment_date = $appointment_date->format('d.m.Y');
                        $appointment_description = $row['appointment_description'];
                        $doctor_name = $row['doctor_name'];
                        $doctor_surname = $row['doctor_surname'];
                        $doctor_ID = $row['doctor_ID'];
                ?>
                <li class="record">
                    <div class="record-data-places">
                        <div class="record-col record-col-id"><?php echo $appointment_ID ?></div>
                        <div class="record-col"><?php echo $formatted_patient_appointment_date ?></div>
                        <div class="record-col record-col-description"><?php echo $appointment_description ?></div>
                        <div class="record-col record-col-doc-id"><?php echo $doctor_ID ?></div>
                        <div class="record-col"><?php echo $doctor_name ?></div>
                        <div class="record-col"><?php echo $doctor_surname ?></div>
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