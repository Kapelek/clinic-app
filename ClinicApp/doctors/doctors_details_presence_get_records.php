<?php
    include('../connection.php');
    session_start();
    if(!isset($_SESSION['account_ID'])){
        header("Location: ../login/login.php");
        exit();
    }

    $doctor_ID=$_GET['id'];
    $query1 = "EXEC doctor_show_details $doctor_ID";
    $result1 = sqlsrv_query($conn, $query1);
    while($row = sqlsrv_fetch_array($result1)) {
        $doctor_name=$row['doctor_name'];
        $doctor_surname=$row['doctor_surname'];
        $doctor_status = $row['doctor_status'];
    }

    if(!isset($_GET['order']) || $_GET['order']=="latests"){
        $query2 = "SELECT * FROM presence_print WHERE doctor_ID=$doctor_ID ORDER BY presence_date DESC";
    }else{
        $query2 = "SELECT * FROM presence_print WHERE doctor_ID=$doctor_ID ORDER BY presence_date ASC";
    }

    $result2 = sqlsrv_query($conn, $query2);

    $data = array();
    while($row = sqlsrv_fetch_array($result2)) {
        $data[] = $row;
    }
?>

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
        <?php
            if($_SESSION['AP']==1){
        ?>
        <a href="doctors_presence_delete.php?doctor_id=<?php echo $doctor_ID ?>&presence_id=<?php echo $presence_ID ?>" class="record-btn record-btn-leave">DELETE PRESENCE</a>
        <?php
            }
        ?>
    </div>
</li>
<?php
    }
?>