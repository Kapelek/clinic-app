<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care for Health Clinic</title>
    <link rel="stylesheet" href="login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php 
        include('../connection.php');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $record=[];
            $login = $_POST["login"];
            $password = $_POST["password"];
            $query = "SELECT * FROM accounts WHERE login COLLATE SQL_Latin1_General_CP1_CS_AS = '$login' AND password COLLATE SQL_Latin1_General_CP1_CS_AS = '$password'";
            $result = sqlsrv_query($conn,$query);
            while($row = sqlsrv_fetch_array($result)){
                $record[] = $row;
            }
            if (!empty($record)) {
                session_start();
                $_SESSION['account_ID'] = $record[0]["account_ID"];
                $_SESSION['AP'] = $record[0]["admin_permission"];
                $_SESSION['login'] = $record[0]["login"];
                $_SESSION['doctor_ID'] = $record[0]["doctor_ID"];
                header("Location: ../main.php");
                exit();
            } else {
                $error_message = "Wrong Login or Password!";
            }   
        }
    ?>
    <div id="logo-bar-bg">
        <div id="logo-bar">
            <img src="../img/logo.png" alt="clinic-logo">
        </div>
    </div>
    <div id="container">
        <div id="container-top">
           CLINIC MANAGEMENT APP - LOGIN
        </div>
        <form method="POST" action="#">
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><b>ERROR/</b> <?php echo $error_message; ?></div>
            <?php
                }
            ?>
            <label for="login">LOGIN</label>
            <input type="text" id="login" name="login" required autocomplete="off"><br>

            <label for="password">PASSWORD</label>
            <input type="password" id="password" name="password" required autocomplete="off"><br>

            <button type="submit" id="submit-btn">LOG IN</button>
        </form>
    </div>
</body>
</html>