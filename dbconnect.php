<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "test";

$con = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");
?>