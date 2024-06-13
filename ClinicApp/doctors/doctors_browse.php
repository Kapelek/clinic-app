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

        $numRows=0;
        $result;
        $result_count;

        $query_spec='SELECT * FROM specialization_print';
        $result_spec=sqlsrv_query($conn,$query_spec);
        $name='';
        $surname='';

        if(isset($_GET['name'])){
            $name=$_GET['name'];
        }
        if(isset($_GET['surname'])){
            $surname=$_GET['surname'];
        }
        if(!isset($_GET['order']) || $_GET['order']=="latests"){
            if(!isset($_GET['doctors-specialization']) || $_GET['doctors-specialization']==""){
                if(!isset($_GET['doctors-status']) || $_GET['doctors-status']=="working"){
                    $query = "SELECT * FROM doctors_print WHERE doctor_status=1 AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID DESC";
                }elseif($_GET['doctors-status']=="not-working"){
                    $query = "SELECT * FROM doctors_print WHERE doctor_status=0 AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID DESC";
                }else{
                    $query = "SELECT * FROM doctors_print WHERE doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID DESC";
                }
            }else{
                $doctors_specialization_ID=$_GET['doctors-specialization'];
                if(!isset($_GET['doctors-status']) || $_GET['doctors-status']=="working"){
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_status=1 and doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID DESC";
                }elseif($_GET['doctors-status']=="not-working"){
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_status=0 and doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID DESC";
                }else{
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID DESC";
                }
            }
        }else if($_GET['order']=="oldests"){
            if(!isset($_GET['doctors-specialization']) || $_GET['doctors-specialization']==""){
                if(!isset($_GET['doctors-status']) || $_GET['doctors-status']=="working"){
                    $query = "SELECT * FROM doctors_print WHERE doctor_status=1 AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID ASC";
                }elseif($_GET['doctors-status']=="not-working"){
                    $query = "SELECT * FROM doctors_print WHERE doctor_status=0 AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID ASC";
                }else{
                    $query = "SELECT * FROM doctors_print WHERE doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID ASC";
                }
            }else{
                $doctors_specialization_ID=$_GET['doctors-specialization'];
                if(!isset($_GET['doctors-status']) || $_GET['doctors-status']=="working"){
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_status=1 and doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID ASC";
                }elseif($_GET['doctors-status']=="not-working"){
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_status=0 and doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID ASC";
                }else{
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_ID ASC";
                }
            }
        }else{
            if(!isset($_GET['doctors-specialization']) || $_GET['doctors-specialization']==""){
                if(!isset($_GET['doctors-status']) || $_GET['doctors-status']=="working"){
                    $query = "SELECT * FROM doctors_print WHERE doctor_status=1 AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_surname ASC";
                }elseif($_GET['doctors-status']=="not-working"){
                    $query = "SELECT * FROM doctors_print WHERE doctor_status=0 AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_surname ASC";
                }else{
                    $query = "SELECT * FROM doctors_print WHERE doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_surname ASC";
                }
            }else{
                $doctors_specialization_ID=$_GET['doctors-specialization'];
                if(!isset($_GET['doctors-status']) || $_GET['doctors-status']=="working"){
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_status=1 and doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_surname ASC";
                }elseif($_GET['doctors-status']=="not-working"){
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_status=0 and doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_surname ASC";
                }else{
                    $query = "SELECT * FROM doctors_print_spec WHERE doctor_specialization_ID=$doctors_specialization_ID AND doctor_name LIKE '$name' + '%' AND doctor_surname LIKE '$surname' + '%' ORDER BY doctor_surname ASC";
                }
            }
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
            <a href="doctors_browse.php">DOCTORS' LIST <span id="cl-h-t-rfrsh">(click HERE to refresh)</span></a>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <form action="#" id="filters"  autocomplete="off" METHOD="GET">
                <div class="filters-top-line">
                    <input type="text" placeholder="Find Doctor By Name" class="search-bar" name="name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>" maxlength="50">
                    <input type="text" placeholder="Find Doctor By Surname" class="search-bar" name="surname" value="<?php echo isset($_GET['surname']) ? $_GET['surname'] : ''; ?>" maxlength="50"> 
                    <h2 id="order-label">Order: </h2>
                    <select name="order" id="order" readonly>
                        <option value="latests" <?php echo isset($_GET['order']) && $_GET['order'] == 'latests' ? 'selected' : ''; ?>>From The Latests (Records)</option>
                        <option value="oldests" <?php echo isset($_GET['order']) && $_GET['order'] == 'oldests' ? 'selected' : ''; ?>>From The Oldests (Records)</option>
                        <option value="aosurname" <?php echo isset($_GET['order']) && $_GET['order'] == 'aosurname' ? 'selected' : ''; ?>>By Surname In Alphabetical Order</option>
                    </select>
                </div>
                <div class="filters-top-line">
                    <h2 id="doctors-status-label">Doctor Status: </h2>
                    <select name="doctors-status" id="doctors-status" readonly>
                        <option value="working" <?php echo isset($_GET['doctors-status']) && $_GET['doctors-status'] == 'working' ? 'selected' : ''; ?>>Working</option>
                        <option value="not-working" <?php echo isset($_GET['doctors-status']) && $_GET['doctors-status'] == 'not-working' ? 'selected' : ''; ?>>Not Working</option>
                        <option value="all" <?php echo isset($_GET['doctors-status']) && $_GET['doctors-status'] == 'all' ? 'selected' : ''; ?>>All</option>
                    </select>
                    <h2 id="doctors-specialization-label">Doctor Specialization: </h2>
                    <select name="doctors-specialization" id="doctors-specialization" readonly>
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
                    <button type="submit" id="submit-btn"><span class="material-symbols-outlined">search</span></button>
                </div>
            </form>
            <div id="container-top-btn-place">
                <!-- <a href="patients_add.php" id="manage-leaves-btn">MANAGE LEAVES</a>
                <a href="patients_add.php" id="manage-presence-btn">MANAGE PRESENCES</a> -->
                <a href="doctors_add.php" id="add-new-btn">ADD NEW DOCTOR</a>
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
            <p id="doctors-count">DOCTORS(<?php echo $numRows ?>)</p>
        </ul>
        <ul id="rows-place">
            <?php
                foreach($data as $row) {
                    $doctor_ID = $row['doctor_ID'];
                    $doctor_name = $row['doctor_name'];
                    $doctor_surname = $row['doctor_surname'];
                    $doctor_date_of_birth = $row['doctor_date_of_birth'];
                    $formatted_doctor_date_of_birth = $doctor_date_of_birth->format('d.m.Y');
                    if(isset($row['doctor_date_of_employment'])){
                        $doctor_date_of_employment = $row['doctor_date_of_employment'];
                        $formatted_doctor_date_of_employment = $doctor_date_of_employment->format('d.m.Y');
                    }
                    $doctor_phone = $row['doctor_phone'];
                    $doctor_status = $row['doctor_status'];
            ?>
                    <li class="record">
                        <div class="record-data-places">
                            <div class="record-col record-col-id"><?php echo $doctor_ID; ?></div>
                            <div class="record-col"><?php echo $doctor_name; ?></div>
                            <div class="record-col"><?php echo $doctor_surname; ?></div>
                            <div class="record-col"><?php echo $formatted_doctor_date_of_birth; ?></div>
                            <div class="record-col"><?php echo $doctor_phone; ?></div>
                            <div class="record-col"><?php echo isset($formatted_doctor_date_of_employment) ? "$formatted_doctor_date_of_employment" : " " ; ?></div>
                            <div class="record-col record-col-working"><?php echo $doctor_status=='1' ? '<span class="material-symbols-outlined">check_box</span>' : '<span class="material-symbols-outlined">check_box_outline_blank</span>';?></div>
                        </div>
                        <div class="record-btns-place">
                            <a href="doctors_details.php?id=<?php echo $doctor_ID?>" class="record-btn">SHOW DETAILS</a>
                            <a href="doctors_leaves_add.php?id=<?php echo $doctor_ID ?>" class="record-btn">ADD LEAVE</a>
                            <a href="doctors_presence_add.php?id=<?php echo $doctor_ID ?>" class="record-btn">ADD PRESENCE</a>
                        </div>
                    </li>
            <?php
                }
            ?>
        </ul>
    </div>
</body>
</html>