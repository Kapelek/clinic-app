<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Presence</title>
    <link rel="stylesheet" href="css/doctors_details_presence.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php
        include('../connection.php');

        $doctor_ID=$_GET['id'];

        $query1 = "EXEC doctor_show_details $doctor_ID";

        if(!isset($_GET['order']) || $_GET['order']=="latests"){
            $query2 = "SELECT * FROM presence_print WHERE doctor_ID=$doctor_ID ORDER BY presence_date DESC";
        }else{
            $query2 = "SELECT * FROM presence_print WHERE doctor_ID=$doctor_ID ORDER BY presence_date ASC";
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
            <p>DOCTOR'S PRESENCE - <?php echo $doctor_name," ",$doctor_surname ?></p>
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
                if($doctor_status==1){
            ?>
            <a href="doctors_presence_add.php?id=<?php echo $doctor_ID ?>" class="container-top-btn">ADD PRESENCE</a>
            <?php
                }
            ?>
        </div>
        <div id="leaves-history">
            <ul id="col-names-bar">
                <li class="col-name col-name-id">ID</li>
                <li class="col-name">DATE</li>
                <p id="presence-count">PRESENCES (<?php echo $numRows ?>)</p>
            </ul>
            <ul id="rows-place">
                <?php
                    foreach($data as $row) {
                        $presence_ID = $row['presence_ID'];
                        $presence_date = $row['presence_date'];
                        $formatted_presence_date = $presence_date->format('d.m.Y');
                ?>
                <li class="record">
                    <div class="record-data-places">
                        <div class="record-col record-col-id"><?php echo $presence_ID ?></div>
                        <div class="record-col"><?php echo $formatted_presence_date ?></div>
                    </div>
                    <div class="record-btns-place">
                        <a href="doctors_presence_delete.php?doctor_id=<?php echo $doctor_ID ?>&presence_id=<?php echo $presence_ID ?>" class="record-btn record-btn-leave">DELETE PRESENCE</a>
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