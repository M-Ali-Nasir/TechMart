<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {

  include_once('../config/config.php');
  require '../../vendor/autoload.php';
  require 'C:\xampp\htdocs\TechMart\backend\Classes\Products.php';
  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);
  $id = 0;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $pdo = new PDO($dsn, $username, $password, $options);
    $product = new Product($pdo);
    $result = $product->deleteProduct($id);
  }




  // $stmt = $con->query("SELECT * FROM subcategories");
  // $subcategories = $stmt->fetch_all(MYSQLI_ASSOC);

  // $stmt = $con->query("SELECT * FROM categories");
  // $categories = $stmt->fetch_all(MYSQLI_ASSOC);

  // $stmt = $con->query("SELECT * FROM products WHERE id = $id");
  // $products = $stmt->fetch_all(MYSQLI_ASSOC);


  header("Location: Products.php");
  exit();
} else {
  header("Location: ../Login.php");
  exit();
}
