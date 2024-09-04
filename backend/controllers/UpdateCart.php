<?php
session_start();


if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];

  require '../../vendor/autoload.php';

  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, ['cache' => false]);

  include_once('../config/config.php');


  require 'C:\xampp\htdocs\TechMart\backend\Classes\Cart.php';


  //include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');

  // Create a PDO instance
  $pdo = new PDO($dsn, $username, $password, $options);



  $cartId = intval($_POST['cartId']);
  $quantity = intval($_POST['quantity']);
  if (isset($_POST['inc'])) {
    $quantity += 1;
  } elseif (isset($_POST['dec'])) {
    $quantity -= 1;
  }



  //$product_id = $_POST['product_id'];
  $user_id = $user['id'];
  //$quantity = $_POST['quantity'];


  $stmt = $pdo->prepare('UPDATE cart SET quantity = :quantity WHERE id = :cart_id');
  $stmt->bindParam('quantity', $quantity);
  $stmt->bindParam('cart_id', $cartId);
  $stmt->execute();


  $cart = new Cart($pdo);

  header("Location: ../Cart.php");
  exit();
}

if (isset($_POST['submitlogin'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  $price = $_POST['price'];

  $data = ['product_id' => $product_id, 'quantity' => $quantity, 'price' => $price];
  $_SESSION['message'] = "Please login to add product to cart";
  $_SESSION['data'] = $data;
  header("Location: ../Login.php");
  exit();
}
