<?php
    include('../connection.php');
    session_start();
    if(!isset($_SESSION['account_ID'])){
        header("Location: ../login/login.php");
        exit();
    }
    $doctor_ID = $_GET['id'];
    $query1 = "EXEC doctor_show_details $doctor_ID";
    $result1 = sqlsrv_query($conn, $query1);
    while($row = sqlsrv_fetch_array($result1)) {
        $doctor_name=$row['doctor_name'];
        $doctor_surname=$row['doctor_surname'];
    }
    if(!isset($_GET['order']) || $_GET['order']=="latests"){
        $query2 = "SELECT * FROM appointments_print WHERE doctor_ID=$doctor_ID ORDER BY appointment_date DESC";
    }else{
        $query2 = "SELECT * FROM appointments_print WHERE doctor_ID=$doctor_ID ORDER BY appointment_date ASC";
    }

    $result2 = sqlsrv_query($conn, $query2);

    $data = array();
    while($row = sqlsrv_fetch_array($result2)) {
        $data[] = $row;
    }
?>

<?php
    foreach($data as $row) {
        $appointment_ID = $row['appointment_ID'];
        $appointment_date = $row['appointment_date'];
        $formatted_patient_appointment_date = $appointment_date->format('d.m.Y');
        $appointment_description = $row['appointment_description'];
        $patient_ID=$row['patient_ID'];
        $patient_name=$row['patient_name'];
        $patient_surname=$row['patient_surname'];
?>
<li class="record">
    <div class="record-data-places">
        <div class="record-col record-col-id"><?php echo $appointment_ID ?></div>
        <div class="record-col"><?php echo $formatted_patient_appointment_date ?></div>
        <div class="record-col record-col-description"><?php echo $appointment_description ?></div>
        <div class="record-col record-col-doc-id"><?php echo $patient_ID ?></div>
        <div class="record-col"><?php echo $patient_name ?></div>
        <div class="record-col"><?php echo $patient_surname ?></div>
    </div>
    <div class="record-btns-place">
        <a href="" class="record-btn">SHOW DETAILS</a>
    </div>
</li>
<?php
    }
?>