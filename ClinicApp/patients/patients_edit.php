<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link rel="stylesheet" href="css/patients_edit.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
     <?php
        // error_reporting(0);
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

        $query1 = "EXEC patient_show_details $patient_ID";
        $query2 = "SELECT * FROM appointments_print WHERE patient_id=$patient_ID";

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);

        $numAppointmentRows=0;
        while($row = sqlsrv_fetch_array($result2)) {
            $numAppointmentRows++;
        }

        while($row = sqlsrv_fetch_array($result1)){
            $patient_name = $row['patient_name'];
            $patient_surname = $row['patient_surname'];
            $patient_date_of_birth = $row['patient_date_of_birth'];
            $formatted_for_input_patient_date_of_birth = $patient_date_of_birth->format('Y-m-d');
            $phone = $row['patient_phone'];
            $patient_phone = $row['patient_phone'];
            $patient_address= $row['patient_address'];
            $patient_city = $row['patient_city'];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $patient_name = $_POST["patient-name"];
            $patient_surname = $_POST["patient-surname"];
            $patient_date_of_birth = $_POST["patient-date-of-birth"];
            $formatted_for_input_patient_date_of_birth=$patient_date_of_birth;
            $patient_phone = $_POST["patient-phone"];
            $patient_city = $_POST["patient-city"];
            $patient_address = $_POST["patient-address"];

            if (empty($_POST["patient-name"]) || empty($_POST["patient-surname"]) || empty($_POST["patient-date-of-birth"])) {
                $error_message = "PLEASE FILL IN ALL THE REQUIRED FIELDS (NAME, SURNAME, DATE OF BIRTH)";
            } else {
                $patient_name = test_input($_POST["patient-name"]);
                $patient_surname = test_input($_POST["patient-surname"]);
                $patient_date_of_birth = test_input($_POST["patient-date-of-birth"]);
                $patient_phone = test_input($_POST["patient-phone"]);
                $patient_city = test_input($_POST["patient-city"]);
                $patient_address = test_input($_POST["patient-address"]);

                $parts_of_date = explode('-', $patient_date_of_birth);

                if (count($parts_of_date) != 3 || !checkdate($parts_of_date[1], $parts_of_date[2], $parts_of_date[0]) || strtotime($patient_date_of_birth) > time()) {
                    $error_message = "INCORRECT DATE OF BIRTH";
                }
                elseif (strlen($patient_name) > 50) {
                    $error_message = "NAME CANNOT BE LONGER THAN 50 CHARACTERS";
                }elseif (strlen($patient_surname) > 50) {
                    $error_message = "SURNAME CANNOT BE LONGER THAN 50 CHARACTERS";
                }elseif (strlen($patient_phone) > 30) {
                    $error_message = "PHONE CANNOT BE LONGER THAN 30 CHARACTERS";
                } elseif (strlen($patient_city) > 50) {
                    $error_message = "CITY CANNOT BE LONGER THAN 50 CHARACTERS";
                }elseif (strlen($patient_address) > 100) {
                    $error_message = "ADDRESS CANNOT BE LONGER THAN 100 CHARACTERS";
                }
                 else {
                    $query3 = "EXEC patient_edit '$patient_ID', $patient_name, '$patient_surname', '$patient_date_of_birth', '$patient_phone', '$patient_city', '$patient_address'";
                    $result3 = sqlsrv_query($conn,$query3);
                    if($result3 === false){
                        $error_message = "QUERY ERROR";
                    }else{
                        header("Location: patients_details.php?id=$patient_ID");
                        exit();
                    }
                }

            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            return $data;
        }
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <a href="patients_edit.php?id=<?php echo $patient_ID ?>"> EDIT PATIENT <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
            <span class="material-symbols-outlined" onclick="window.location.href='patients_details.php?id=<?php echo $patient_ID?>'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <?php
                if($numAppointmentRows==0){
            ?>
            <a href="patients_delete.php?id=<?php echo $patient_ID ?>" class="container-top-btn" id="delete-patient-btn">DELETE PATIENT</a>
            <?php
                }
            ?>
        </div>
        <form id="informations" method="POST" action="#" autocomplete="off">
            <?php if (!empty($error_message)) { ?>
            <div class="error-message"><b>ERROR/</b> <?php echo $error_message; ?></div>
            <?php
                }
            ?>
            <div class="info-line info-line-id"><span class="data-name">ID:</span> <?php echo $patient_ID ?></div>
            <div class="info-line"><span class="data-name">NAME:</span> <input type="text" value="<?php echo $patient_name?>" name="patient-name" maxlength="50"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">SURNAME:</span> <input type="text" value="<?php echo $patient_surname?>" name="patient-surname" maxlength="50"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">DATE OF BIRTH:</span> <input type="date" name="patient-date-of-birth" value="<?php echo $formatted_for_input_patient_date_of_birth ?>"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">PHONE:</span> <input type="text" value="<?php echo $patient_phone ?>" name="patient-phone" maxlength="30"> </div>
            <div class="info-line"><span class="data-name">CITY:</span> <input type="text" value="<?php echo $patient_city ?>" name="patient-city" maxlength="50"> </div>
            <div class="info-line"><span class="data-name">ADDRESS:</span> <input type="text" value="<?php echo $patient_address ?>" name="patient-address" maxlength="100"> </div>
            <div id="submit-btn-line">
                <button type="submit" id="submit-btn">SAVE CHANGES</button>
            </div>
        </form>
    </div>
</body>
</html>