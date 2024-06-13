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
       $query2 = "SELECT * FROM leaves_print WHERE doctor_ID=$doctor_ID ORDER BY holiday_end_date DESC";
   }else{
       $query2 = "SELECT * FROM leaves_print WHERE doctor_ID=$doctor_ID ORDER BY holiday_end_date ASC";
   }

   $result2 = sqlsrv_query($conn, $query2);

   $data = array();
   while($row = sqlsrv_fetch_array($result2)) {
       $data[] = $row;
   }
?>

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
        <?php
            if($_SESSION['AP']==1){
        ?>
        <a href="doctors_leaves_edit.php?id=<?php echo $holiday_ID ?>" class="record-btn">EDIT LEAVE</a>
        <a href="doctor_leaves_delete.php?doctor_id=<?php echo $doctor_ID ?>&leave_id=<?php echo $holiday_ID ?>" class="record-btn record-btn-leave">DELETE LEAVE</a>
        <?php 
            }
        ?>
    </div>
</li>
<?php
    }
?>