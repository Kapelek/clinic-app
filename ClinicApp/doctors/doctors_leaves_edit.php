<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Leave</title>
    <link rel="stylesheet" href="css/doctors_leaves_edit.css">
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
        
        $holiday_ID=$_GET['id'];

        $query1 = "SELECT * FROM leaves_print WHERE holiday_id=$holiday_ID";
        $query2 = "SELECT * FROM holiday_type_print";

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);

        while($row = sqlsrv_fetch_array($result1)){
            $holiday_type_ID = $row['holiday_type_ID'];
            $holiday_type_name = $row['holiday_type_name'];
            $holiday_start_date = $row['holiday_start_date'];
            $formatted_for_input_holiday_start_date = $holiday_start_date->format('Y-m-d');
            $holiday_end_date = $row['holiday_end_date'];
            $formatted_for_input_holiday_end_date = $holiday_end_date->format('Y-m-d');
            $doctor_ID = $row['doctor_ID'];
            $const_doctor_ID = $doctor_ID;
            $default_doctor_ID = $doctor_ID;
            $doctor_name = $row['doctor_name'];
            $doctor_surname = $row['doctor_surname'];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $doctor_ID=$_POST['doctor-id'];
            $holiday_type_ID=$_POST['holiday-type-id'];
            $holiday_start_date=$_POST['holiday-start-date'];
            $formatted_for_input_holiday_start_date = $holiday_start_date;
            $holiday_end_date=$_POST['holiday-end-date'];
            $formatted_for_input_holiday_end_date = $holiday_end_date;
            if (empty($doctor_ID) || empty($holiday_type_ID) || empty($holiday_start_date) || empty($holiday_end_date)) {
                $error_message="PLEASE FILL IN ALL THE REQUIRED FIELDS (DOCTOR ID, TYPE, START DATE, END DATE)";
            }else{
                $doctor_ID=test_input($doctor_ID);
                $holiday_type_ID=test_input($holiday_type_ID);
                $holiday_start_date=test_input($holiday_start_date);
                $holiday_end_date=test_input($holiday_end_date);

                $parts_of_date_1 = explode('-', $holiday_start_date);
                $parts_of_date_2 = explode('-', $holiday_end_date);
                if(count($parts_of_date_1) != 3 || !checkdate($parts_of_date_1[1], $parts_of_date_1[2], $parts_of_date_1[0])){
                    $error_message = "INCORRECT START DATE";
                }elseif(count($parts_of_date_2) != 3 || !checkdate($parts_of_date_2[1], $parts_of_date_2[2], $parts_of_date_2[0])){
                    $error_message = "INCORRECT END DATE";
                }elseif(strtotime($holiday_start_date) > strtotime($holiday_end_date)){
                    $error_message = "START DATE CAN'T BE LATER THAN END DATE";
                }else{
                    $query3="EXEC leave_edit '$holiday_ID','$doctor_ID','$holiday_type_ID','$holiday_start_date','$holiday_end_date'";
                    $result3=sqlsrv_query($conn,$query3);
                    if($result3===false){
                        $error_message="QUERY ERROR";
                    }else{
                        header("Location: doctors_details_leaves.php?id=$doctor_ID");
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
            <a href="doctors_leaves_edit.php?id=<?php echo $holiday_ID ?>"> EDIT LEAVE <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
            <span class="material-symbols-outlined" onclick="window.location.href='doctors_details_leaves.php?id=<?php echo $default_doctor_ID?>'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <a href="doctor_leaves_delete.php?doctor_id=<?php echo $const_doctor_ID ?>&leave_id=<?php echo $holiday_ID ?>" class="container-top-btn" id="delete-leave-btn">DELETE LEAVE</a>
        </div>
        <form id="informations" method="POST" action="#" autocomplete="off">
            <?php if (!empty($error_message)) { ?>
            <div class="error-message"><b>ERROR/</b> <?php echo $error_message; ?></div>
            <?php
                }
            ?>
            <div class="info-line info-line-id"><span class="data-name">ID:</span> <?php echo $holiday_ID ?></div>
            <div class="info-line"><span class="data-name">DOCTOR ID: </span> <input type="number" value="<?php echo $doctor_ID?>" name="doctor-id"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">HOLIDAY TYPE:</span>
                <select name="holiday-type-id" readonly>
                    <?php
                        while($row = sqlsrv_fetch_array($result2)){
                            $to_select_holiday_type_ID = $row['holiday_type_ID'];
                            $to_select_holiday_type_name = $row['holiday_type_name'];
                    ?>
                    <option value="<?php echo $to_select_holiday_type_ID ?>" <?php echo $holiday_type_ID == $to_select_holiday_type_ID ? 'selected' : ''; ?>><?php echo $to_select_holiday_type_name ?></option>
                    <?php
                        }
                    ?>
                </select> 
            <span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">START DATE: </span> <input type="date" value="<?php echo $formatted_for_input_holiday_start_date ?>" name="holiday-start-date"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">END DATE: </span> <input type="date" value="<?php echo $formatted_for_input_holiday_end_date ?>" name="holiday-end-date"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div id="submit-btn-line">
                <button type="submit" id="submit-btn">SAVE CHANGES</button>
            </div>
        </form>
    </div>
</body>
</html>