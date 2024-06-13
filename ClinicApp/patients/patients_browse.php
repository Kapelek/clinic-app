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

        $name='';
        $surname='';

        $numRows=0;

        if(isset($_GET['name'])){
            $name=$_GET['name'];
        }
        if(isset($_GET['surname'])){
            $surname=$_GET['surname'];
        }

        
        if(!isset($_GET['order']) || $_GET['order']=="latests"){
            $query="SELECT * FROM patients_print WHERE patient_name LIKE '$name' + '%' AND patient_surname LIKE '$surname' + '%' ORDER BY patient_ID DESC";
        }else if($_GET['order']=="oldests"){
            $query="SELECT * FROM patients_print WHERE patient_name LIKE '$name' + '%' AND patient_surname LIKE '$surname' + '%' ORDER BY patient_ID ASC";
        }else{
            $query="SELECT * FROM patients_print WHERE patient_name LIKE '$name' + '%' AND patient_surname LIKE '$surname' + '%' ORDER BY patient_surname ASC";
        }


        $result=sqlsrv_query($conn,$query);
        $data = array();
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
            $numRows++;
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
                <input type="text" placeholder="Find Patient By Name" class="search-bar" name="name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>" maxlength="50">
                <input type="text" placeholder="Find Patient By Surname" class="search-bar" name="surname" value="<?php echo isset($_GET['surname']) ? $_GET['surname'] : ''; ?>" maxlength="50"> 
                <h2 id="order-label">Order: </h2>
                <select name="order" id="order" readonly>
                    <option value="latests" <?php echo isset($_GET['order']) && $_GET['order'] == 'latests' ? 'selected' : ''; ?>>From The Latests (Records)</option>
                    <option value="oldests" <?php echo isset($_GET['order']) && $_GET['order'] == 'oldests' ? 'selected' : ''; ?>>From The Oldests (Records)</option>
                    <option value="aosurname" <?php echo isset($_GET['order']) && $_GET['order'] == 'aosurname' ? 'selected' : ''; ?>>By Surname In Alphabetical Order</option>
                </select>
                <button type="submit" id="submit-btn"><span class="material-symbols-outlined">search</span></button>
            </form>
            <a href="patients_add.php" id="add-new-btn">ADD NEW PATIENT</a>
        </div>
        <ul id="col-names-bar">
            <li class="col-name col-name-id">ID</li>
            <li class="col-name">NAME</li>
            <li class="col-name">SURNAME</li>
            <li class="col-name">DATE OF BIRTH</li>
            <li class="col-name">PHONE</li>
            <p id="patients-count">PATIENTS(<?php echo $numRows ?>)</p>
        </ul>
        <ul id="rows-place">
            <?php
                foreach($data as $row) {
                    $patient_ID = $row['patient_ID'];
                    $patient_name = $row['patient_name'];
                    $patient_surname = $row['patient_surname'];
                    $patient_date_of_birth = $row['patient_date_of_birth'];
                    $formatted_patient_date_of_birth = $patient_date_of_birth->format('d.m.Y');
                    $phone = $row['patient_phone'];
                    $patient_phone = $row['patient_phone'];
            ?>
                    <li class="record">
                        <div class="record-data-places">
                            <div class="record-col record-col-id"><?php echo $patient_ID; ?></div>
                            <div class="record-col"><?php echo $patient_name; ?></div>
                            <div class="record-col"><?php echo $patient_surname; ?></div>
                            <div class="record-col"><?php echo $formatted_patient_date_of_birth; ?></div>
                            <div class="record-col"><?php echo $patient_phone; ?></div>
                        </div>
                        <div class="record-btns-place">
                            <a href="patients_details.php?id=<?php echo $patient_ID?>" class="record-btn">SHOW DETAILS</a>
                            <a href="#" class="record-btn">NEW APPOINTMENT</a>
                        </div>
                    </li>
            <?php
                }
            ?>
        </ul>
    </div>
</body>
</html>