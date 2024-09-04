<?php
session_start();


if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  if ($_POST) {

    require 'C:\xampp\htdocs\TechMart\backend\Classes\AddToCart.php';


    include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');

    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password, $options);

    // Create a Register instance
    $register = new AddToCart($pdo);


    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);



    //$product_id = $_POST['product_id'];
    $user_id = $user['id'];
    //$quantity = $_POST['quantity'];

    $stmt = $pdo->prepare('SELECT price FROM products WHERE id = :product_id');
    $stmt->execute(['product_id' => $product_id]);
    $price = $stmt->fetch();

    $price = $price['price'];




    $result = $register->addToCart($product_id, $user_id, $quantity, $price);

    if ($result == 1) {
      $message = "Product Added to Cart";
      echo json_encode($message);
    } else {
      echo "error";
    }
  }
} else {
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
}
