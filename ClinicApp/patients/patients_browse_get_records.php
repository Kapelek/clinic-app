<?php
    error_reporting(0);
    include('../connection.php');
    session_start();
    if(!isset($_SESSION['account_ID'])){
        header("Location: ../login/login.php");
        exit();
    }

    $name = $_GET['name'];
    $surname = $_GET['surname'];
    if (!isset($_GET['order']) || $_GET['order'] == "latests") {
        $query = "SELECT * FROM patients_print WHERE patient_name LIKE '$name' + '%' AND patient_surname LIKE '$surname' + '%' ORDER BY patient_ID DESC";
    } else if ($_GET['order'] == "oldests") {
        $query = "SELECT * FROM patients_print WHERE patient_name LIKE '$name' + '%' AND patient_surname LIKE '$surname' + '%' ORDER BY patient_ID ASC";
    } else {
        $query = "SELECT * FROM patients_print WHERE patient_name LIKE '$name' + '%' AND patient_surname LIKE '$surname' + '%' ORDER BY patient_surname ASC";
    }

    $result = sqlsrv_query($conn, $query);
    $data = array();
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
?>

<?php
    foreach ($data as $row) {
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