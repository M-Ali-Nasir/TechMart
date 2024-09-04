<?php



session_start();
require 'C:\xampp\htdocs\TechMart\backend\Classes\Cart.php';
include_once('TwigConfig.php');
include_once('config/config.php');


$pdo = new PDO($dsn, $username, $password, $options);


// Create a Login instance
$cart = new Cart($pdo);

if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];


  $cart = $cart->getCartItems($user['id']);
  $totalItems = count($cart);
  if (isset($_SESSION['err'])) {
    $err = $_SESSION['err'];
    unset($_SESSION['err']);
    echo $twig->render('checkout.twig', ['cart' => $cart, 'totalItems' => $totalItems, 'err' => $err]);
  } else {
    echo $twig->render('checkout.twig', ['cart' => $cart, 'totalItems' => $totalItems]);
  }
}
