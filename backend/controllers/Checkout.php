<?php
session_start();
/* coded by Rahul Barui ( https://github.com/Rahul-Barui ) */
require 'C:\xampp\htdocs\TechMart\backend\Classes\CheckOut.php';
include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');


$pdo = new PDO($dsn, $username, $password, $options);
$err = [];
// Create a Register instance
$checkout = new CheckOut($pdo);

$user = $_SESSION['user'];
$customerId = $user['id'];

if (isset($_POST['payBtn'])) {


  if (isset($_POST['name']) && !empty($_POST['name'])) {
    $specialChars = '@!#$%^&*()-=+[{]};:\'",<.>/?\\|12334567890';
    if (strpbrk($_POST['name'], $specialChars) !== false) {
      $err['name'] = "Name can only contain Alphabetic charcters";
    } else {
      $_SESSION['customerName'] = $_POST['name'];
    }
  } else {
    $err['name'] = "Name cannot be empty";
  }
  if (isset($_POST['phone']) && !empty($_POST['phone'])) {
    $specialChars = '@!#$%^&*()-_=[{]};:\'",<.>/?\\|abcdefghijklmnopqrstuvwxyzABCDEFHGIJKLMNOPQRSTUVWXYZ';
    if (strpbrk($_POST['phone'], $specialChars) !== false) {
      $err['phone'] = "Mobile Number cannot contain special and alphabetic characters";
    } else {
      $_SESSION['customerPhone'] = $_POST['phone'];
    }
  } else {
    $err['phone'] = "Mobile Number cannot be empty";
  }
  if (isset($_POST['address']) && !empty($_POST['address'])) {
    $specialChars = '!$%^&*()-=+[{]};:\'",<>/?\\|';
    if (strpbrk($_POST['address'], $specialChars) !== false) {
      $err['address'] = "Address can contain Alphanumeric, @, _,# characters only";
    } else {
      $_SESSION['customerAddress'] = $_POST['address'];
    }
  } else {
    $err['address'] = "Address cannot be empty";
  }
  if (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) {
    $specialChars = '@!#$%^&*()-=+[{]};:\'",<.>/?\\|abcdefghijklmnopqrstuvwxyzABCDEFHGIJKLMNOPQRSTUVWXYZ';
    if (strpbrk($_POST['zipcode'], $specialChars) !== false) {
      $err['zipcode'] = "ZipCode can contain only digits";
    } else {
      $_SESSION['customerZipcode'] = $_POST['zipcode'];
    }
  } else {
    $err['zipcode'] = "ZipCode cannot be empty";
  }

  if (count($err) == 0) {


    $checkout->stripeCheckout($customerId);
  } else {
    $_SESSION['err'] = $err;
    header("Location: ../Checkout.php");
    exit();
  }
}
