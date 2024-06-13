<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Leaves</title>
    <link rel="stylesheet" href="css/doctors_details_leaves.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php
        include('../connection.php');

        $doctor_ID=$_GET['id'];

        $query1 = "EXEC doctor_show_details $doctor_ID";

        if(!isset($_GET['order']) || $_GET['order']=="latests"){
            $query2 = "SELECT * FROM leaves_print WHERE doctor_ID=$doctor_ID ORDER BY holiday_end_date DESC";
        }else{
            $query2 = "SELECT * FROM leaves_print WHERE doctor_ID=$doctor_ID ORDER BY holiday_end_date ASC";
        }

        $result1 = sqlsrv_query($conn, $query1);
        $result2 = sqlsrv_query($conn, $query2);

        while($row = sqlsrv_fetch_array($result1)) {
            $doctor_name=$row['doctor_name'];
            $doctor_surname=$row['doctor_surname'];
            $doctor_status = $row['doctor_status'];
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
            <p>DOCTOR'S LEAVES - <?php echo $doctor_name," ",$doctor_surname ?></p>
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
            <?php 
                if ($doctor_status==1){
            ?>
            <a href="doctors_leaves_add.php?id=<?php echo $doctor_ID ?>" class="container-top-btn">ADD LEAVE</a>
            <?php
                }
            ?>
        </div>
        <div id="leaves-history">
            <ul id="col-names-bar">
                <li class="col-name col-name-id">ID</li>
                <li class="col-name">TYPE</li>
                <li class="col-name">START DATE</li>
                <li class="col-name">END DATE</li>
                <p id="leaves-count">LEAVES (<?php echo $numRows ?>)</p>
            </ul>
            <ul id="rows-place">
                <?php
                    foreach($data as $row) {
                        $holiday_ID = $row['holiday_ID'];
                        $holiday_type_name = $row['holiday_type_name'];
                        $holiday_start_date = $row['holiday_start_date'];
                        $formatted_holiday_start_date = $holiday_start_date->format('d.m.Y');
                        $holiday_end_date = $row['holiday_end_date'];
                        $formatted_holiday_end_date = $holiday_end_date->format('d.m.Y');
                ?>
                <li class="record">
                    <div class="record-data-places">
                        <div class="record-col record-col-id"><?php echo $holiday_ID ?></div>
                        <div class="record-col"><?php echo $holiday_type_name ?></div>
                        <div class="record-col"><?php echo $formatted_holiday_start_date ?></div>
                        <div class="record-col"><?php echo $formatted_holiday_end_date ?></div>
                    </div>
                    <div class="record-btns-place">
                        <a href="doctors_leaves_edit.php?id=<?php echo $holiday_ID ?>" class="record-btn">EDIT LEAVE</a>
                        <a href="doctor_leaves_delete.php?doctor_id=<?php echo $doctor_ID ?>&leave_id=<?php echo $holiday_ID ?>" class="record-btn record-btn-leave">DELETE LEAVE</a>
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