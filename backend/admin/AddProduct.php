<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {


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
  if (isset($_POST['addProduct'])) {
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

    $result = $product->addProduct($name, $description, $price, $category_id, $subcategory_id, $image);

    if ($result == 1) {
      echo "Changes Saved Successfully";
      header("Location: Products.php");
      exit();
    } else {
      $err = $result;
      $_SESSION['err'] = $err;
      header("Location: AddProduct.php?id=$id");
      exit();
    }
  }



  $stmt = $con->query("SELECT * FROM subcategories");
  $subcategories = $stmt->fetch_all(MYSQLI_ASSOC);

  $stmt = $con->query("SELECT * FROM categories");
  $categories = $stmt->fetch_all(MYSQLI_ASSOC);

  $stmt = $con->query("SELECT * FROM products");
  $product = $stmt->fetch_assoc();

  if (count($err) > 0) {
    echo $twig->render('admin/addProduct.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'product' => $product, 'err' => $err]);
  } elseif (strlen($message) > 0) {
    echo $twig->render('admin/addProduct.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'product' => $product, 'message' => $message]);
  } else {
    echo $twig->render('admin/addProduct.twig', ['categories' => $categories, 'subcategories' => $subcategories, 'product' => $product]);
  }
} else {
  header("Location: ../Login.php");
  exit();
}
