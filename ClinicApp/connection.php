<?php
$serverName = "localhost";
$connectionOptions = array(
    "Database" => "clinic",
    "Uid" => "",
    "PWD" => "",
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
?>