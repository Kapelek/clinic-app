<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care for Health Clinic</title>
    <link rel="stylesheet" href="main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php
        include('connection.php');
        session_start();
        if(!isset($_SESSION['account_ID'])){
            header("Location: login/login.php");
            exit();
        }else{
            $account_ID=$_SESSION['account_ID'];
            $login=$_SESSION['login'];
            $doctor_ID_session=$_SESSION['doctor_ID'];
            $AP=$_SESSION['AP'];
        }
    ?>
    <div id="logo-bar-bg">
        <div id="logo-bar">
            <img src="img/logo.png" alt="clinic-logo">
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            CLINIC MANAGEMENT APP 
            <p class="account-info">
                <?php echo "Acccount ID: <span style='color:#e3e3e3'>".$account_ID."</span> Login: <span style='color:#e3e3e3'>".$login."</span>"?>
                <?php
                    if(!empty($doctor_ID_session)){
                        $query = "SELECT * FROM doctors WHERE doctor_ID='$doctor_ID_session'";
                        $result = sqlsrv_query($conn,$query);
                        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
                            $doctor_name = $row["doctor_name"];
                            $doctor_surname = $row["doctor_surname"];
                        }
                        echo "<span style='color:#020202'>/</span> Doctor: <span style='color:#e3e3e3'>".$doctor_name." ".$doctor_surname." (".$doctor_ID_session.")"."</span>";
                    }else{
                        echo "";
                    }
                    if($AP == 1){
                        echo "<span style='color:#020202'> /</span><span style='color:red'> FULL PERMISSION </span>";
                    }
                ?>
            </p>
            <a class="btn" id="settings-btn" onclick="toggleWindow()" target="display-window" href="settings/settings.php"><span class="material-symbols-outlined">settings</span></a>
            <div class="btn" onclick="logout();"><span class="material-symbols-outlined">logout</span></div>
        </div>
        <div class="container-content">
            <a href="#" class="item" target="display-window" onclick="toggleWindow();">
                <div class="item-decoration-line"></div>
                <div class="item-left">
                    <span class="material-symbols-outlined">badge</span>
                </div>
                <div class="item-right">
                    APPOINTMENTS & PRESCRIPTIONS
                </div>
                <div class="item-hover-bg">
                    <h2>APPOINTMENTS</h2>
                    <ul>
                        <li>add new appointment</li>
                        <li>browse appointments</li>
                        <li>edit appointments</li>
                    </ul>
                    <h2 class="item-hover-bg-bottom"><span class="material-symbols-outlined">keyboard_double_arrow_right</span></h2>
                </div>
            </a>
            <a href="patients/patients_browse.php" class="item" target="display-window" onclick="toggleWindow();">
                <div class="item-decoration-line"></div>
                <div class="item-left">
                    <span class="material-symbols-outlined">patient_list</span>
                </div>
                <div class="item-right">
                    PATIENTS 
                </div>
                <div class="item-hover-bg">
                    <h2>PATIENTS</h2>
                    <ul>
                        <li>browse patients</li>
                        <li>edit patients</li>
                        <li>add new patient</li>
                    </ul>
                    <h2 class="item-hover-bg-bottom"><span class="material-symbols-outlined">keyboard_double_arrow_right</span></h2>
                </div>
            </a>
            <a href="#" class="item" target="display-window" onclick="toggleWindow();">
                <div class="item-decoration-line"></div>
                <div class="item-left">
                    <span class="material-symbols-outlined">medication</span>
                </div>
                <div class="item-right">
                    MEDICINE
                </div>
                <div class="item-hover-bg">
                    <h2>MEDICINE</h2>
                    <ul>
                        <li>browse medicine</li>
                        <li>browse concerns</li>
                    </ul>
                    <h2 class="item-hover-bg-bottom"><span class="material-symbols-outlined">keyboard_double_arrow_right</span></h2>
                </div>
            </a>
            <a href="doctors/doctors_browse.php" class="item" target="display-window" onclick="toggleWindow();">
                <div class="item-decoration-line"></div>
                <div class="item-left">
                    <span class="material-symbols-outlined">stethoscope</span>
                </div>
                <div class="item-right">
                    SPECIALISTS / DOCTORS
                </div>
                <div class="item-hover-bg">
                    <h2>SPECIALISTS / DOCTORS</h2>
                    <ul>
                        <li>browse specialists</li>
                        <li>edit specialists</li>
                        <li>add new specialist</li>
                        <li>manage the presence of specialists</li>
                        <li>manage the leaves of specialists</li>
                    </ul>
                    <h2 class="item-hover-bg-bottom"><span class="material-symbols-outlined">keyboard_double_arrow_right</span></h2>
                </div>
            </a>
        </div>
    </div>
    <div id="window-bg">
        <span class="material-symbols-outlined" id="window-close" onclick="toggleWindow();">close</span>
        <iframe id="display-window" name="display-window"></iframe>
    </div>
</body>
<script src="main.js"></script>
</html>