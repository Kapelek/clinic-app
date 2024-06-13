<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Presence</title>
    <link rel="stylesheet" href="css/doctors_presence_add.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
     <?php
        // error_reporting(0);
        include('../connection.php');

        if(isset($_GET['id'])){
            $doctor_ID=$_GET['id'];
            $doctor_ID_from_GET=$_GET['id'];
        }

        $query1 = "SELECT * FROM holiday_type_print";
        $result1 = sqlsrv_query($conn, $query1);

        $today = time();
        $today_date_formatted_for_input = date("Y-m-d",$today);
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $presence_date = $_POST['presence-date'];
            $doctor_ID = $_POST['doctor-id'];
            if (empty($presence_date) || empty($doctor_ID)) {
                $error_message="PLEASE FILL IN ALL THE REQUIRED FIELDS (DOCTOR ID, DATE)";
            }else{
                $parts_of_date = explode('-', $presence_date);
                if(count($parts_of_date) != 3 || !checkdate($parts_of_date[1], $parts_of_date[2], $parts_of_date[0]) || strtotime($presence_date) > $today){
                    $error_message = "INCORRECT DATE";
                }else{
                    $query2="EXEC presence_add '$presence_date','$doctor_ID'";
                    $result2=sqlsrv_query($conn,$query2);
                    if($result2===false){
                        $error_message="QUERY ERROR";
                    }else{
                        header("Location: doctors_details_presence.php?id=$doctor_ID");
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
            <a href="doctors_presence_add.php?id=<?php echo $doctor_ID_from_GET ?>"> ADD NEW PRESENCE <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
            <span class="material-symbols-outlined" onclick="window.location.href='<?php echo isset($doctor_ID_from_GET) ? 'doctors_details_presence.php?id=' . $doctor_ID_from_GET : 'jd' ?>'" id="back-btn">arrow_back</span>
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

            <div class="info-line"><span class="data-name">DOCTOR ID: </span> <input type="number" value="<?php echo isset($doctor_ID) ? $doctor_ID: '' ?>" name="doctor-id"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div class="info-line"><span class="data-name">DATE: </span> <input type="date" value="<?php echo isset($presence_date) ? "$presence_date" : "$today_date_formatted_for_input" ?>" name="presence-date"><span class="required-hover-info">This field cannot be empty!</span></div>
            <div id="submit-btn-line">
                <button type="submit" id="submit-btn">SAVE CHANGES</button>
            </div>
        </form>
    </div>
</body>
</html>