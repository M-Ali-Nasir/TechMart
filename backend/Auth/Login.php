<?php
session_start();
if (isset($_POST['login'])) {

  require 'C:\xampp\htdocs\TechMart\backend\Classes\Auth.php';

  require 'C:\xampp\htdocs\TechMart\backend\Classes\AddToCart.php';
  include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');

  // Create a PDO instance
  $pdo = new PDO($dsn, $username, $password, $options);

  // Create a Login instance
  $auth = new Auth($pdo);

  // Example: Login a user
  $email = $_POST['email'];
  $password = $_POST['password'];

  $result = $auth->loginUser($email, $password);

  if ($result == 1) {


    $user = $_SESSION['user'];

    if (isset($_SESSION['data']) && $user['role'] == "customer") {
      $data = $_SESSION['data'];
      $user_id = $user['id'];
      $product_id = $data['product_id'];
      $quantity = $data['quantity'];
      $price = $data['price'];

      $register = new AddToCart($pdo);
      $result = $register->addToCart($product_id, $user_id, $quantity, $price);
      if ($result == 1) {
        $message = "Product Added to Cart";
        $_SESSION['message'] = $message;
      }

      unset($_SESSION['data']);
    }

    if ($_SESSION['role'] == "admin") {
      header("Location: ../admin/Dashboard.php");
      exit();
    } elseif ($_SESSION['role'] == "customer") {
      header("Location: ../Home.php");
      exit();
    }
  } else {
    $_SESSION['error'] = $result;
    header("Location: ../Login.php");
    exit();
  }
};
