<?php
session_start();
require 'C:\xampp\htdocs\TechMart\backend\Classes\Cart.php';
include_once('TwigConfig.php');
include_once('config/config.php');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);



$pdo = new PDO($dsn, $username, $password, $options);

// Create a Login instance
$cart = new Cart($pdo);

if (isset($_SESSION['user'])) {

  if (!($redis->get('categories'))) {
    $stmt = $con->query("SELECT * FROM categories");
    $categories = $stmt->fetch_all(MYSQLI_ASSOC);
    $redis->setex("categories", 1200, serialize($categories));
  } else {
    $categories = unserialize($redis->get("categories"));
  }

  if (!($redis->get('subcategories'))) {
    $stmt = $con->query("SELECT * FROM subcategories");
    $subcategories = $stmt->fetch_all(MYSQLI_ASSOC);
    $redis->setex("subcategories", 1200, serialize($subcategories));
  } else {
    $subcategories = unserialize($redis->get("subcategories"));
  }


  $user = $_SESSION['user'];
  $cart = $cart->getCartItems($user['id']);
  $userId = $user['id'];
  $stmt = $con->query("SELECT count(*) FROM cart WHERE user_id=$userId");
  $totalItems = $stmt->fetch_assoc();
  $totalItems = $totalItems['count(*)'];
  echo $twig->render('cart.twig', ['cart' => $cart, 'user' => $user, 'categories' => $categories, 'subcategories' => $subcategories, 'totalItems' => $totalItems]);
}
