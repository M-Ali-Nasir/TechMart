<?php
$dsn = 'mysql:host=localhost;dbname=tech_mart;charset=utf8';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tech_mart";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
  die('Connection Failed' . mysqli_connect_error());
}
