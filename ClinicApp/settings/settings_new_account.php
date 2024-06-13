<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Account</title>
    <link rel="stylesheet" href="css/settings_new_account.css">
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
        }else if($_SESSION['AP']==0){
            header("Location: ../main.php");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = $_POST["login"];
            $new_password_v1 = $_POST["new-password-v1"];
            $new_password_v2 = $_POST["new-password-v2"];
            if(isset($_POST['AP']) && !empty($_POST['AP'])){
                $AP = $_POST['AP'];
            }else{
                $AP = 0;
            }
            if(isset($_POST['doctor_ID']) && !empty($_POST['doctor_ID'])){
                $doctor_ID=$_POST['doctor_ID'];
            }else{
                $doctor_ID = NULL;
            }

            if (empty($login) || empty($new_password_v1) || empty($new_password_v2)) {
                $error_message = "PLEASE FILL IN ALL THE REQUIRED FIELDS (LOGIN, PASSWORD x2)";
            } else {
                if(strlen($new_password_v1)<6 || strlen($new_password_v1)>25){
                    $error_message = "NEW PASSWORD HAS TO BE AT LEAST 6 CHARACTERS LONG, UP TO 25";
                }else if($new_password_v1!=$new_password_v2){
                    $error_message = "NEW PASSWORDS ARE NOT THE SAME";
                }else if(strlen($login)>50){
                    $error_message = "LOGIN HAS TO BE MAXIMUM 50 CHARACTERS";
                }else{
                    $query1 = "EXEC account_add '$login','$new_password_v1',$AP,'$doctor_ID'";
                    $result1 = sqlsrv_query($conn,$query1);
                    if($result1 === false){
                        $error_message = "QUERY ERROR";
                    }else{
                        header("Location: settings.php");
                        exit();
                    }
                }
            }
        }
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <a>SETTINGS -  CREATE NEW ACCOUNT</a>
            <span class="material-symbols-outlined" onclick="window.location.href='settings.php'" id="back-btn">arrow_back</span>
        </div>
    </div>
    <div id="container">
        <form id="container-top" method="POST" action="#">
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><b>ERROR/</b> <?php echo $error_message; ?></div>
            <?php
                }
            ?>
            LOGIN: <input type="text" name="login" maxlength="50">
            PASSWORD: <input type="password" name="new-password-v1" maxlength="25">
            PASSWORD: <input type="password" name="new-password-v2" maxlength="25">
            ADMIN PERMISSION: <input type="checkbox" name="AP" value="1">
            DOCTOR ID: <input type="number" name="doctor_ID" autocomplete="off">
            <button class="btn">CREATE</button>
        </form>
    </div>

</body>
</html>