<?php
$host = "";
$user = "";
$pass = "";
$db_name = "";

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("HOTDOG");
}
?>