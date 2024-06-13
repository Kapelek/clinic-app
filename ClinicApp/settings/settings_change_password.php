<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/settings_change_password.css">
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $old_password = $_POST["old-password"];
            $new_password_v1 = $_POST["new-password-v1"];
            $new_password_v2 = $_POST["new-password-v2"];
            $account_ID= $_SESSION['account_ID'];
            $query1 = "SELECT password FROM accounts WHERE account_ID=$account_ID";
            $result1 = sqlsrv_query($conn,$query1);
            while($row = sqlsrv_fetch_array($result1)){
                $current_password = $row['password'];
            }
            if (empty($old_password) || empty($new_password_v1) || empty($new_password_v2)) {
                $error_message = "PLEASE FILL IN ALL THE REQUIRED FIELDS (OLD PASSWORD, NEW PASSWORD x2)";
            } else {
                if(strlen($new_password_v1)<6 || strlen($new_password_v1)>25){
                    $error_message = "NEW PASSWORD HAS TO BE AT LEAST 6 CHARACTERS LONG, UP TO 25";
                }else if($new_password_v1!=$new_password_v2){
                    $error_message = "NEW PASSWORDS ARE NOT THE SAME";
                }else if($current_password!=$old_password){
                    $error_message = "OLD PASSWORD IS INCORRECT";
                }else{
                    $query2 = "EXEC account_password_edit '$account_ID','$new_password_v1'";
                    $result2 = sqlsrv_query($conn,$query2);
                    if($result2 === false){
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
            <a>SETTINGS -  CHANGE PASSWORD</a>
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
            <span>OLD PASSWORD: <input type="password" name="old-password" maxlength="25"></span>
            <span>NEW PASSWORD: <input type="password" name="new-password-v1" maxlength="25"></span>
            <span>NEW PASSWORD: <input type="password" name="new-password-v2" maxlength="25"></span>
            <button class="btn">CHANGE</button>
        </form>
    </div>

</body>
</html>