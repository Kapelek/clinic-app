<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="css/doctors_edit.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="js/doctors_details.js"></script>
</head>
<body>
    <?php
        include('../connection.php');

        $doctor_ID=$_GET['id'];

        $query1 = "EXEC doctor_show_details $doctor_ID";
        $query2 = "EXEC doctor_show_specializations $doctor_ID";
        $query3 = "SELECT * FROM appointments_print WHERE doctor_id=$doctor_ID";
        $query4 = "SELECT * FROM leaves_print WHERE doctor_id=$doctor_ID";
        $query5 = "SELECT * FROM presence_print WHERE doctor_id=$doctor_ID";
        $query8 = "EXEC doctor_show_specializations $doctor_ID";
        $query6 = "SELECT * FROM specialization_print";
        $query7 = "SELECT * FROM specialization_print";

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);
        $result3 = sqlsrv_query($conn,$query3);
        $result4 = sqlsrv_query($conn,$query4);
        $result5 = sqlsrv_query($conn,$query5);
        $result8 = sqlsrv_query($conn,$query8);
        $result6 = sqlsrv_query($conn,$query6);
        $result7 = sqlsrv_query($conn,$query7);

        
        $numRelatedRows=0;
        while($row = sqlsrv_fetch_array($result3)) {
            $numRelatedRows++;
        }
        while($row = sqlsrv_fetch_array($result4)) {
            $numRelatedRows++;
        }
        while($row = sqlsrv_fetch_array($result5)) {
            $numRelatedRows++;
        }

        while($row = sqlsrv_fetch_array($result8)) {
            $numRelatedRows++;
        }

        while($row = sqlsrv_fetch_array($result1)){
            $doctor_name = $row['doctor_name'];
            $doctor_surname = $row['doctor_surname'];
            $doctor_date_of_birth = $row['doctor_date_of_birth'];
            $formatted_for_input_doctor_date_of_birth = $doctor_date_of_birth->format('Y-m-d');
            $doctor_phone = $row['doctor_phone'];
            $doctor_address= $row['doctor_address'];
            $doctor_city = $row['doctor_city'];
            $doctor_status = $row['doctor_status'];
            $doctor_status_old = $doctor_status;
            $doctor_salary = $row['doctor_salary'];
            $formatted_for_input_doctor_salary = floatval(str_replace(',', '', $doctor_salary));
            if(isset($row['doctor_date_of_employment'])){
                $doctor_date_of_employment = $row['doctor_date_of_employment'];
                $formatted_doctor_date_of_employment = $doctor_date_of_employment->format('Y-m-d');
            }
        }

        $doctor_specializations_ids_array = array();
        $specializations_names_array = array();
        $doctor_specializations_to_delete_array = array();
        

        while($row = sqlsrv_fetch_array($result2)){
            $doctor_specializations_ids_array[] = $row['doctor_specialization_ID'];
        }

        while($row = sqlsrv_fetch_array($result6)){
            $specializations_names_array[] = $row['specialization_name'];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            foreach($doctor_specializations_ids_array as $value ){
                $checkbox = "checkbox_" . $value;
                if(empty($_POST[$checkbox])){
                    $doctor_specializations_to_delete_array [] = $value;
                }else{
                    $index = array_search($value, $doctor_specializations_to_delete_array);
                    if ($index !== false) {
                        unset($array[$index]);
                    }
                }
            }
            $doctor_name = $_POST["doctor-name"];
            $doctor_surname = $_POST["doctor-surname"];
            $doctor_date_of_birth = $_POST["doctor-date-of-birth"];
            $formatted_for_input_doctor_date_of_birth=$doctor_date_of_birth;
            $doctor_phone = $_POST["doctor-phone"];
            $doctor_city = $_POST["doctor-city"];
            $doctor_address = $_POST["doctor-address"];
            if(isset($_POST["doctor-status"])){
                $doctor_status=1;
            }else{
                $doctor_status=0;
            }
            $doctor_salary = $_POST['doctor-salary'];
            $formatted_for_input_doctor_salary = floatval(str_replace(',', '', $doctor_salary));
            $doctor_new_specialization_id = $_POST["doctors-new-specialization"];

            if (empty($_POST["doctor-name"]) || empty($_POST["doctor-surname"]) || empty($_POST["doctor-date-of-birth"]) || empty($_POST["doctor-phone"]) || empty($_POST["doctor-city"]) || empty($_POST["doctor-address"]) || ($doctor_status == 1 && empty($doctor_salary))) {
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
                    $error_count = 0;
                    if($doctor_status==1){
                        if($doctor_status_old==0){
                            $query9="EXEC doctor_edit '$doctor_ID', '$doctor_name', '$doctor_surname', '$doctor_address', '$doctor_city', '$doctor_date_of_birth', '$today','$doctor_salary', '$doctor_phone', '$doctor_status'";
                        }else{
                            $query9="EXEC doctor_edit '$doctor_ID', '$doctor_name', '$doctor_surname', '$doctor_address', '$doctor_city', '$doctor_date_of_birth', '$formatted_doctor_date_of_employment',$doctor_salary, '$doctor_phone', $doctor_status";
                        }
                    }else{
                        $query9="EXEC doctor_edit '$doctor_ID', '$doctor_name', '$doctor_surname', '$doctor_address', '$doctor_city', '$doctor_date_of_birth', NULL ,0, '$doctor_phone', '$doctor_status'"; 
                    }
                    
                    $result9 = sqlsrv_query($conn,$query9);
                    if($result9 === false){
                        $error_message = "QUERY ERROR";
                        $error_count++;
                    }
                    
                    if(!empty($doctor_specializations_to_delete_array)){
                        foreach($doctor_specializations_to_delete_array as $value){
                            $query11="EXEC doctor_specialization_delete '$doctor_ID','$value'";
                            $result11 = sqlsrv_query($conn,$query11);
                            if($result11 === false){
                                $error_message = "QUERY ERROR";
                                $error_count++;
                            }
                        }
                    }

                    if(!empty($doctor_new_specialization_id)){
                        $query10="EXEC doctor_specialization_add '$doctor_ID','$doctor_new_specialization_id'";
                        $result10 = sqlsrv_query($conn,$query10);
                        if($result10 === false){
                            $error_message = "QUERY ERROR";
                            $error_count++;
                        }
                    }
                    if($error_count==0){
                        header("Location: doctors_details.php?id=$doctor_ID");
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
        <a href="doctors_edit.php?id=<?php echo $doctor_ID ?>"> EDIT DOCTOR <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
            <span class="material-symbols-outlined" onclick="window.location.href='doctors_details.php?id=<?php echo $doctor_ID?>'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <?php 
                if ($numRelatedRows==0){
            ?>
            <a href="doctors_delete.php?id=<?php echo $doctor_ID ?>" class="container-top-btn" id="delete-doctor-btn">DELETE DOCTOR</a>
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
            <div class="info-line"><span class="data-name">ID:</span> <?php echo $doctor_ID ?></div>
            <div class="info-line"><span class="data-name">NAME:</span> <input type="text" value="<?php echo $doctor_name ?>" name="doctor-name" maxlength="50"> <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">SURNAME:</span> <input type="text" value="<?php echo $doctor_surname ?>" name="doctor-surname" maxlength="50"> <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">DATE OF BIRTH:</span> <input type="date" value="<?php echo $formatted_for_input_doctor_date_of_birth ?>" name="doctor-date-of-birth"> <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">PHONE:</span> <input type="text" value="<?php echo $doctor_phone ?>" name="doctor-phone" maxlength="30"> <span class="required-hover-info">This field cannot be empty!</span> </div>
            <div class="info-line"><span class="data-name">CITY:</span> <input type="text" value="<?php echo $doctor_city ?>" name="doctor-city" maxlength="50"> <span class="required-hover-info">This field cannot be empty!</span> </div>
            <div class="info-line"><span class="data-name">ADDRESS:</span> <input type="text" value="<?php echo $doctor_address ?>" name="doctor-address" maxlength="100"><span class="required-hover-info">This field cannot be empty!</span> </div>
            <div class="info-line">
                <span class="data-name">SPECIALIZATIONS:</span> 
                <?php foreach($doctor_specializations_ids_array as $value ){ ?> 
                    <label>
                    <input type="checkbox" name="checkbox_<?php echo $value ?>" value="<?php echo $value ?>" <?php echo in_array($value, $doctor_specializations_to_delete_array) ? "" : "checked" ?>>
                    <?php echo $specializations_names_array[$value-1]?>
                    </label>
                <p>,&nbsp;</p>
                <?php } ?>
                <select name="doctors-new-specialization" id="doctors-new-specialization" readonly>
                    <option value=""></option>
                    <?php
                        while($row = sqlsrv_fetch_array($result7)){
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
            <div class="info-line"><span class="data-name">DOCTOR STATUS:</span> <input type="checkbox" onclick="showSalary()" name="doctor-status" id="checkbox-doctor-status" value="1" <?php echo $doctor_status=='1' ? 'class="checked" checked' : '' ?>></div>
            <div class="info-line" id="salary-line" <?php echo ($doctor_status==1) ? "style='display:block'" : "style='display:none'" ?>><span class="data-name">SALARY:</span> <input min="1" step="any" type="number" name="doctor-salary" value="<?php echo $formatted_for_input_doctor_salary; ?>">&nbsp; $ <span class="required-hover-info">This field cannot be empty!</span></div>
            <div id="submit-btn-line">
                <button type="submit" id="submit-btn">SAVE CHANGES</button>
            </div>
        </form>
    </div>
    <script src="js/doctors_edit.js"></script>
</body>
</html>