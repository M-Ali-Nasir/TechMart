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
  $id = 0;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  }

  $err = [];
  include_once('../config/config.php');
  if (isset($_SESSION['err'])) {
    $err = $_SESSION['err'];
    unset($_SESSION['err']);
  }

  $message = "";
  if (isset($_POST['editProduct'])) {
    $pdo = new PDO($dsn, $username, $password, $options);
    $product = new Product($pdo);

    $id = $_POST['id'];
    $_SESSION['id'] = $id;
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $image = "";
    if (isset($_FILES['image'])) {
      $image = $_FILES['image'];
    }

    $result = $product->editProduct($id, $name, $description, $price, $category_id, $subcategory_id, $image);

    if ($result == 1) {
      $message = "Changes Saved Successfuly";
      $_SESSION['message'] = $message;
      header("Location: Products.php");
      exit();
    } else {
      $err = $result;
      $_SESSION['err'] = $err;
      header("Location: EditProduct.php?id=$id");
      exit();
    }
  }



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

  $stmt = $con->query("SELECT * FROM products WHERE id = $id");
  $product = $stmt->fetch_assoc();

  if (count($err) > 0) {
    echo $twig->render('admin/editProduct.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'product' => $product, 'err' => $err]);
  } elseif (strlen($message) > 0) {
    echo $twig->render('admin/editProduct.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'product' => $product, 'message' => $message]);
  } else {
    echo $twig->render('admin/editProduct.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'product' => $product]);
  }
} else {
  header("Location: ../Login.php");
  exit();
}
