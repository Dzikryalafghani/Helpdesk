<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "helpdesk_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn){
    die("Koneksi databse gagal: " . mysqli_connect_error());
}
?>