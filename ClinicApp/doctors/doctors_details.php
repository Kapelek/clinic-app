<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Details</title>
    <link rel="stylesheet" href="css/doctors_details.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="js/doctors_details.js"></script>
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
        $query2 = "EXEC doctor_show_specializations $doctor_ID";

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);

        while($row = sqlsrv_fetch_array($result1)){
            $doctor_name = $row['doctor_name'];
            $doctor_surname = $row['doctor_surname'];
            $doctor_date_of_birth = $row['doctor_date_of_birth'];
            $formatted_doctor_date_of_birth = $doctor_date_of_birth->format('d.m.Y');
            $doctor_phone = $row['doctor_phone'];
            $doctor_address= $row['doctor_address'];
            $doctor_city = $row['doctor_city'];
            $doctor_status = $row['doctor_status'];
            $doctor_salary = $row['doctor_salary'];
            $formatted_doctor_salary = number_format($doctor_salary, 2, ".");
            if(isset($row['doctor_date_of_employment'])){
                $doctor_date_of_employment = $row['doctor_date_of_employment'];
                $formatted_doctor_date_of_employment = $doctor_date_of_employment->format('d.m.Y');
            }
        }

        $doctor_specializations_array = array();
        while($row = sqlsrv_fetch_array($result2)){
            $doctor_specializations_array[] = $row['doctor_specialization_name'];
        }
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <p>DOCTOR'S DETAILS - <?php echo $doctor_name," ",$doctor_surname?></p>
            <span class="material-symbols-outlined" onclick="window.location.href='doctors_browse.php'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <?php 
                if ($doctor_status==1 && $_SESSION['AP']==1){
            ?>
            <a href="doctors_leaves_add.php?id=<?php echo $doctor_ID ?>" class="container-top-btn">ADD LEAVE</a>
            <a href="doctors_presence_add.php?id=<?php echo $doctor_ID ?>" class="container-top-btn">ADD PRESENCE</a>
            <?php 
                }
            ?>
            <?php
                if ($_SESSION['AP']==1){
            ?>
                <a href="doctors_edit.php?id=<?php echo $doctor_ID ?>" class="container-top-btn">EDIT DOCTOR</a>
            <?php 
                }
            ?>
        </div>
        <div id="informations">
            <div class="info-line"><span class="data-name">ID:</span> <?php echo $doctor_ID ?></div>
            <div class="info-line"><span class="data-name">NAME:</span> <?php echo $doctor_name ?></div>
            <div class="info-line"><span class="data-name">SURNAME:</span> <?php echo $doctor_surname ?></div>
            <div class="info-line"><span class="data-name">DATE OF BIRTH:</span> <?php echo $formatted_doctor_date_of_birth ?></div>
            <div class="info-line"><span class="data-name">PHONE:</span> <?php echo $doctor_phone ?></div>
            <div class="info-line"><span class="data-name">ADDRESS:</span> <?php echo $doctor_city,", ",$doctor_address ?></div>
            <div class="info-line"><span class="data-name">SPECIALIZATIONS:</span> <?php foreach($doctor_specializations_array as $value ){echo " ",$value,",";} ?></div>
            <div class="info-line info-line-works"><span class="data-name">DOCTOR STATUS:</span> <?php echo $doctor_status=='1' ? 'WORKING' : 'NOT WORKING' ?></div>
            <?php 
                if($doctor_status==1){ 
            ?>
            <div class="info-line info-line-works"><span class="data-name">DATE OF EMPLOYMENT:</span> <?php echo $formatted_doctor_date_of_employment ?></div>
            <div class="info-line info-line-works"><span class="data-name">SALARY:</span> <?php echo $formatted_doctor_salary ,'$'?></div>
            <?php
                }
            ?>
        </div>
        <div id="under-btns">
            <div id="in-under-btns">
                <a href="doctors_details_appointments.php?id=<?php echo $doctor_ID ?>" class="under-btn">DOCTOR'S APPOINTMENTS</a>
                <a href="doctors_details_leaves.php?id=<?php echo $doctor_ID ?>" class="under-btn">DOCTOR'S LEAVES</a>
                <a href="doctors_details_presence.php?id=<?php echo $doctor_ID?>" class="under-btn">DOCTOR'S PRESENCE</a>
            </div>
        </div>
    </div>
</body>
</html>