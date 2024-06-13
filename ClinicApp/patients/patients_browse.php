<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Patients</title>
    <link rel="stylesheet" href="css/patients_browse.css">
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
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <a href="patients_browse.php">PATIENTS' LIST <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <form action="#" id="filters"  autocomplete="off" METHOD="GET">
                <input type="text" placeholder="Find Patient By Name" class="search-bar" name="name" maxlength="50" oninput="updateRecords()">
                <input type="text" placeholder="Find Patient By Surname" class="search-bar" name="surname" maxlength="50" oninput="updateRecords()"> 
                <h2 id="order-label">Order: </h2>
                <select name="order" id="order" readonly onchange="updateRecords()">
                    <option value="latests">From The Latests (Records)</option>
                    <option value="oldests" >From The Oldests (Records)</option>
                    <option value="aosurname">By Surname In Alphabetical Order</option>
                </select>
            </form>
            <a href="patients_add.php" id="add-new-btn">ADD NEW PATIENT</a>
        </div>
        <ul id="col-names-bar">
            <li class="col-name col-name-id">ID</li>
            <li class="col-name">NAME</li>
            <li class="col-name">SURNAME</li>
            <li class="col-name">DATE OF BIRTH</li>
            <li class="col-name">PHONE</li>
            <p id="patients-count">PATIENTS(<a id="num-rows"></a>)</p>
        </ul>
        <ul id="rows-place"></ul>
    </div>
    <script src="js/patient_browse.js"></script>
</body>
</html>