<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Doctors</title>
    <link rel="stylesheet" href="css/doctors_browse.css">
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
        $query_spec='SELECT * FROM specialization_print';
        $result_spec=sqlsrv_query($conn,$query_spec);
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <a href="doctors_browse.php">DOCTORS' LIST <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <form action="#" id="filters"  autocomplete="off" METHOD="GET">
                <div class="filters-top-line">
                    <input type="text" placeholder="Find Doctor By Name" class="search-bar" name="name" maxlength="50" oninput="updateRecords()">
                    <input type="text" placeholder="Find Doctor By Surname" class="search-bar" name="surname" maxlength="50" oninput="updateRecords()"> 
                    <h2 id="order-label">Order: </h2>
                    <select name="order" id="order" readonly onchange="updateRecords()">
                        <option value="latests">From The Latests (Records)</option>
                        <option value="oldests">From The Oldests (Records)</option>
                        <option value="aosurname">By Surname In Alphabetical Order</option>
                    </select>
                </div>
                <div class="filters-top-line">
                    <h2 id="doctors-status-label">Doctor Status: </h2>
                    <select name="doctors-status" id="doctors-status" readonly onchange="updateRecords()">
                        <option value="working">Working</option>
                        <option value="not-working">Not Working</option>
                        <option value="all">All</option>
                    </select>
                    <h2 id="doctors-specialization-label">Doctor Specialization: </h2>
                    <select name="doctors-specialization" id="doctors-specialization" readonly onchange="updateRecords()">
                        <option value=""></option>
                        <?php
                            while($row = sqlsrv_fetch_array($result_spec)){
                                $specialization_ID = $row['specialization_id'];
                                $specialization_name = $row['specialization_name'];
                        ?>
                        <option value="<?php echo $specialization_ID ?>" <?php echo isset($_GET['doctors-specialization']) && $_GET['doctors-specialization'] == $specialization_ID ? 'selected' : ''; ?>><?php echo $specialization_name ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
            </form>
            <div id="container-top-btn-place">
                <!-- <a href="patients_add.php" id="manage-leaves-btn">MANAGE LEAVES</a>
                <a href="patients_add.php" id="manage-presence-btn">MANAGE PRESENCES</a> -->
                <?php
                    if($_SESSION['AP']==1){
                ?>
                <a href="doctors_add.php" id="add-new-btn">ADD NEW DOCTOR</a>
                <?php
                    }
                ?>
            </div>
        </div>
        <ul id="col-names-bar">
            <li class="col-name col-name-id">ID</li>
            <li class="col-name">NAME</li>
            <li class="col-name">SURNAME</li>
            <li class="col-name">DATE OF BIRTH</li>
            <li class="col-name">PHONE</li>
            <li class="col-name">DATE OF EMPLOYMENT</li>
            <li class="col-name col-name-working">WORKS</li>
            <p id="doctors-count">DOCTORS(<a id="num-rows"></a>)</p>
        </ul>
        <ul id="rows-place">
        </ul>
    </div>
    <script src="js/doctors_browse.js"></script>
</body>
</html>