<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {

  require '../../vendor/autoload.php';
  require 'C:\xampp\htdocs\TechMart\backend\Classes\Products.php';
  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);
  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);
  include_once('../config/config.php');


  $err = [];
  $message = "";

  if (isset($_GET['id'])) {

    $id = $_GET['id'];


    $stmt = $con->query("SELECT * FROM orders WHERE id = $id");
    $order = $stmt->fetch_assoc();

    $userId = $order['user_id'];

    $stmt = $con->query("SELECT * FROM order_items WHERE order_id=$id");
    $order_items = $stmt->fetch_all(MYSQLI_ASSOC);


    $stmt = $con->query("SELECT * FROM order_address WHERE order_id = $id");
    $order_address = $stmt->fetch_assoc();


    if (!($redis->get('products'))) {
      $stmt = $con->query("SELECT * FROM products");
      $products = $stmt->fetch_all(MYSQLI_ASSOC);
      $redis->setex("products", 1200, serialize($products));
    } else {
      $products = unserialize($redis->get("products"));
    }


    $stmt = $con->query("SELECT * FROM users WHERE id=$userId");
    $user = $stmt->fetch_assoc();


    echo $twig->render('admin/orderView.twig', ['order' => $order, 'order_items' => $order_items, 'order_address' => $order_address, 'products' => $products, 'user' => $user]);
  }
}
