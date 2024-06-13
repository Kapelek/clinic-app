<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/settings.css">
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
    ?>
    <div id="title-bar-bg">
        <div id="title-bar">
            <a>SETTINGS</a>
        </div>
    </div>
    <div id="container">
        <div id="container-top">
            <a class="btn" href="settings_change_password.php">CHANGE PASSWORD</a>
            <?php
                if($_SESSION['AP']==1){
            ?>
            <a class="btn" href="settings_new_account.php">NEW ACCOUNT</a>
            <?php
                }
            ?>
        </div>
    </div>

</body>
</html>