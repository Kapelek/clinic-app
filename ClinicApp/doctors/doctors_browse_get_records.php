<?php
    include('../connection.php');
    session_start();
    if(!isset($_SESSION['account_ID'])){
        header("Location: ../login/login.php");
        exit();
    }
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
            <?php 
                if($doctor_status==1 && $_SESSION['AP']==1){
            ?>
                <a href="doctors_leaves_add.php?id=<?php echo $doctor_ID ?>" class="record-btn">ADD LEAVE</a>
                <a href="doctors_presence_add.php?id=<?php echo $doctor_ID ?>" class="record-btn">ADD PRESENCE</a>
            <?php
                }
            ?>
        </div>
    </li>
<?php
    }
?>