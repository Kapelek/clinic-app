<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Doctor</title>
    <link rel="stylesheet" href="css/doctors_add.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="js/doctors_details.js"></script>
</head>
<body>
    <?php
        error_reporting(0);
        include('../connection.php');

        $query1 = "SELECT * FROM specialization_print";

        $result1 = sqlsrv_query($conn,$query1);


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $doctor_name = $_POST["doctor-name"];
            $doctor_surname = $_POST["doctor-surname"];
            $doctor_date_of_birth = $_POST["doctor-date-of-birth"];
            $formatted_for_input_doctor_date_of_birth=$doctor_date_of_birth;
            $doctor_phone = $_POST["doctor-phone"];
            $doctor_city = $_POST["doctor-city"];
            $doctor_address = $_POST["doctor-address"];
            $doctor_salary = $_POST['doctor-salary'];
            $formatted_for_input_doctor_salary = floatval(str_replace(',', '', $doctor_salary));
            $doctor_new_specialization_id = $_POST["doctors-new-specialization"];

            if (empty($_POST["doctor-name"]) || empty($_POST["doctor-surname"]) || empty($_POST["doctor-date-of-birth"]) || empty($_POST["doctor-phone"]) || empty($_POST["doctor-city"]) || empty($_POST["doctor-address"]) || empty($doctor_salary)) {
                $error_message = "PLEASE FILL IN ALL THE REQUIRED FIELDS (NAME, SURNAME, DATE OF BIRTH, PHONE, CITY, ADDRESS)";
            } else {
                $doctor_name = test_input($_POST["doctor-name"]);
                $doctor_surname = test_input($_POST["doctor-surname"]);
                $doctor_date_of_birth = test_input($_POST["doctor-date-of-birth"]);
                $doctor_phone = test_input($_POST["doctor-phone"]);
                $doctor_city = test_input($_POST["doctor-city"]);
                $doctor_address = test_input($_POST["doctor-address"]);

                $parts_of_date = explode('-', $doctor_date_of_birth);

                if (count($parts_of_date) != 3 || !checkdate($parts_of_date[1], $parts_of_date[2], $parts_of_date[0]) || strtotime($doctor_date_of_birth) > time()) {
                    $error_message = "INCORRECT DATE OF BIRTH";
                }
                elseif (strlen($doctor_name) > 50) {
                    $error_message = "NAME CANNOT BE LONGER THAN 50 CHARACTERS";
                }elseif (strlen($doctor_surname) > 50) {
                    $error_message = "SURNAME CANNOT BE LONGER THAN 50 CHARACTERS";
                }elseif (strlen($doctor_phone) > 30) {
                    $error_message = "PHONE CANNOT BE LONGER THAN 30 CHARACTERS";
                } elseif (strlen($doctor_city) > 50) {
                    $error_message = "CITY CANNOT BE LONGER THAN 50 CHARACTERS";
                }elseif (strlen($doctor_address) > 100) {
                    $error_message = "ADDRESS CANNOT BE LONGER THAN 100 CHARACTERS";
                }elseif ($doctor_salary <1) {
                    $error_message = "SALARY CANNOT BE LOWER THAN 1";
                }
                 else {
                    $today = date("Y-m-d");

                    if(!empty($doctor_new_specialization_id)){
                        $query2 = "EXEC doctor_add_with_specialization '$doctor_name', '$doctor_surname', '$doctor_address', '$doctor_city', '$doctor_date_of_birth' , '$today', $doctor_salary, '$doctor_phone', 1, $doctor_new_specialization_id";
                        $result2 = sqlsrv_query($conn,$query2);
                        if($result2 === false){
                            $error_message = "QUERY ERROR";
                        }else{
                            header("Location: doctors_browse.php");
                            exit();
                        }
                    }else{
                        $query2 = "EXEC doctor_add_without_specialization '$doctor_name', '$doctor_surname', '$doctor_address', '$doctor_city', '$doctor_date_of_birth' , '$today', $doctor_salary, '$doctor_phone', 1";
                        $result2 = sqlsrv_query($conn,$query2);
                        if($result2 === false){
                            $error_message = "QUERY ERROR";
                        }else{
                            header("Location: doctors_browse.php");
                            exit();
                        }
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
        <a href="doctors_add.php"> ADD NEW DOCTOR <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
            <span class="material-symbols-outlined" onclick="window.location.href='doctors_browse.php'" id="back-btn">arrow_back</span>
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
            <div class="info-line"><span class="data-name">NAME:</span> <input type="text" value="<?php echo $doctor_name ?>" name="doctor-name" maxlength="50"> <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">SURNAME:</span> <input type="text" value="<?php echo $doctor_surname ?>" name="doctor-surname" maxlength="50"> <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">DATE OF BIRTH:</span> <input type="date" value="<?php echo $formatted_for_input_doctor_date_of_birth ?>" name="doctor-date-of-birth"> <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">PHONE:</span> <input type="text" value="<?php echo $doctor_phone ?>" name="doctor-phone" maxlength="30"> <span class="required-hover-info">This field cannot be empty!</span> </div>
            <div class="info-line"><span class="data-name">CITY:</span> <input type="text" value="<?php echo $doctor_city ?>" name="doctor-city" maxlength="50"> <span class="required-hover-info">This field cannot be empty!</span> </div>
            <div class="info-line"><span class="data-name">ADDRESS:</span> <input type="text" value="<?php echo $doctor_address ?>" name="doctor-address" maxlength="100"><span class="required-hover-info">This field cannot be empty!</span> </div>
            <div class="info-line">
                <span class="data-name">SPECIALIZATIONS:</span> 
                <select name="doctors-new-specialization" id="doctors-new-specialization" readonly>
                    <option value=""></option>
                    <?php
                        while($row = sqlsrv_fetch_array($result1)){
                            $specialization_ID = $row['specialization_id'];
                            $specialization_name = $row['specialization_name'];
                    ?>
                    <option value="<?php echo $specialization_ID ?>" <?php echo isset($doctor_new_specialization_id) && $doctor_new_specialization_id == $specialization_ID ? 'selected' : ''; ?>><?php echo $specialization_name ?></option>
                    <?php
                        }
                    ?>
                </select>
                <span class="material-symbols-outlined spec-btn" onclick="showNewSpecWindow()">add</span> 
                <span class="required-hover-info">You can add only one specialization at once!</span>
            </div>
            <div class="info-line" id="salary-line"><span class="data-name">SALARY:</span> <input min="1" step="any" type="number" name="doctor-salary" value="<?php echo $formatted_for_input_doctor_salary; ?>">&nbsp; $ <span class="required-hover-info">This field cannot be empty!</span></div>
            <div id="submit-btn-line">
                <button type="submit" id="submit-btn">SAVE CHANGES</button>
            </div>
        </form>
    </div>
    <script src="js/doctors_add.js"></script>
</body>
</html>