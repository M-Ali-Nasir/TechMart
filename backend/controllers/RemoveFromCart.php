<?php
session_start();


if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];

  require 'C:\xampp\htdocs\TechMart\backend\Classes\Cart.php';


  include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');

  // Create a PDO instance
  $pdo = new PDO($dsn, $username, $password, $options);

  // Create a Register instance
  $cart = new Cart($pdo);


  if (isset($_GET['cartId'])) {
    $cartId = $_GET['cartId'];

    $result = $cart->removeFromCart($cartId);

    if ($result == 1) {
      header("Location: ../Cart.php");
      exit();
    } else {
      echo "error";
    }
  }
}
