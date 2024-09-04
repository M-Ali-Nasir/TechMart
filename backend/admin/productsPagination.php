<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {

  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);

  require '../../vendor/autoload.php';
  require 'C:\xampp\htdocs\TechMart\backend\Classes\Products.php';
  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);

  include_once('../config/config.php');
  $err = [];
  $message = "";

  if (isset($_SESSION['imageName'])) {
    print_r($_SESSION['imageName']);
  }


  if (isset($_POST['page'])) {
    $page = $_POST['page'];
  } else {
    $page = 1;
  }

  $limit = 12;
  $offset = ($page - 1) * $limit;




  if (!($redis->get('subcategories'))) {
    $stmt = $con->query("SELECT * FROM subcategories");
    $subcategories = $stmt->fetch_all(MYSQLI_ASSOC);
    $redis->setex("subcategories", 1200, serialize($subcategories));
  } else {
    $subcategories = unserialize($redis->get("subcategories"));
  }

  if (!($redis->get('categories'))) {
    $stmt = $con->query("SELECT * FROM categories");
    $categories = $stmt->fetch_all(MYSQLI_ASSOC);
    $redis->setex("categories", 1200, serialize($categories));
  } else {
    $categories = unserialize($redis->get("categories"));
  }


  $stmt = $con->query("SELECT * FROM products LIMIT $limit OFFSET $offset");
  $products = $stmt->fetch_all(MYSQLI_ASSOC);


  $stmt = $con->query("SELECT COUNT(*) FROM products");
  $totalProducts = $stmt->fetch_assoc();
  $totalProducts = $totalProducts['COUNT(*)'];
  $totalPages = ceil($totalProducts / $limit);

  if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
  }

  $response = [
    "products" => $products,
    "categories" => $categories,
    "subcategories" => $subcategories,
    "currentPage" => $page,
    "totalPages" => $totalPages,
  ];

  echo json_encode($response);

  //   echo $twig->render('admin/products.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'products' => $products, 'message' => $message]);
  // } else {
  //   header("Location: ../Login.php");
  //   exit();
}
