<?php
if (isset($_POST['register'])) {
  session_start();
  require 'C:\xampp\htdocs\TechMart\backend\Classes\RegisterUser.php';


  include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');

  // Create a PDO instance
  $pdo = new PDO($dsn, $username, $password, $options);

  // Create a Register instance
  $register = new Register($pdo);

  // Register a new user

  $username = $_POST['name'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $passwordRepeat = $_POST['passwordRepeat'];


  $result = $register->registerUser($username, $password, $email, $passwordRepeat);

  if ($result == 1) {
    $message = "User Registered Successfuly Please Login";
    $_SESSION['message'] = $message;
    header("Location: ../Login.php");
    exit();
  } else {

    $_SESSION['error'] = $result;
    header("Location: ../Register.php");
    exit();
  }
};
