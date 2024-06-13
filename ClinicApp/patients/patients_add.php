<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Patient</title>
    <link rel="stylesheet" href="css/patients_add.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
     <?php
        error_reporting(0);
        include('../connection.php');

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $patient_name = $_POST["patient-name"];
            $patient_surname = $_POST["patient-surname"];
            $patient_date_of_birth = $_POST["patient-date-of-birth"];
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
                    $query1 = "EXEC patient_add $patient_name, '$patient_surname', '$patient_date_of_birth', '$patient_phone', '$patient_city', '$patient_address'";
                    $result1 = sqlsrv_query($conn,$query1);
                    if($result1 === false){
                        $error_message = "QUERY ERROR";
                    }else{
                        header("Location: patients_browse.php");
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
            <a href="patients_add.php"> ADD NEW PATIENT <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
            <span class="material-symbols-outlined" onclick="window.location.href='patients_browse.php'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
        </div>
        <form id="informations" method="POST" action="#" autocomplete="off">
            <?php if (!empty($error_message)) { ?>
            <div class="error-message"><b>ERROR/</b> <?php echo $error_message; ?></div>
            <?php
                }
            ?>
            <div class="info-line"><span class="data-name">NAME:</span> <input type="text" name="patient-name" maxlength="50" value="<?php echo $patient_name ?>"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">SURNAME:</span> <input type="text" name="patient-surname" maxlength="50" value="<?php echo $patient_surname ?>"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">DATE OF BIRTH:</span> <input type="date" name="patient-date-of-birth" value="<?php echo $patient_date_of_birth ?>"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">PHONE:</span> <input type="text" name="patient-phone" maxlength="30" value="<?php echo $patient_phone ?>"> </div>
            <div class="info-line"><span class="data-name">CITY:</span> <input type="text" name="patient-city" maxlength="50" value="<?php echo $patient_city ?>"> </div>
            <div class="info-line"><span class="data-name">ADDRESS:</span> <input type="text" name="patient-address" maxlength="100" value="<?php echo $patient_address ?>"> </div>
            <div id="submit-btn-line">
                <button type="submit" id="submit-btn">ADD</button>
            </div>
        </form>
    </div>
</body>
</html>