<?php
if (isset($_POST['logout'])) {
  session_start();
  require 'C:\xampp\htdocs\TechMart\backend\Classes\Auth.php';
  include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');

  // Create a PDO instance
  $pdo = new PDO($dsn, $username, $password, $options);

  // Create a Login instance
  $auth = new Auth($pdo);


  $logout = $auth->logoutUser();
  if ($logout) {
    header("Location: ../Home.php");
    exit();
  }


  // if ($result == 1) {
  //   $message = "User Registered Successfuly Please Login";
  //   $_SESSION['message'] = $message;
  //   header("Location: ../Login.php");
  //   exit();
  // } else {

  //   $_SESSION['error'] = $result;
  //   header("Location: ../Register.php");
  //   exit();
  // }
};
