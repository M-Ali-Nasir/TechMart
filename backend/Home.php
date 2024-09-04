<?php

//Home.php

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);




session_start();

require '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, ['cache' => false]);

include_once('config/config.php');

// Database connection
$pdo = new PDO($dsn, $username, $password, $options);

// Fetch categories
$categoriesStmt = $pdo->prepare("SELECT * FROM categories");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch subcategories
$subcategoriesStmt = $pdo->prepare("SELECT * FROM subcategories");
$subcategoriesStmt->execute();
$subcategories = $subcategoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Set the number of items per page
$limit = 12;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch products (default to all products if no category/subcategory is selected)
$query = "SELECT * FROM products LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total number of products for pagination
$totalProductsStmt = $pdo->prepare("SELECT COUNT(*) FROM products");
$totalProductsStmt->execute();
$totalProducts = $totalProductsStmt->fetchColumn();
$totalPages = ceil($totalProducts / $limit);

// Store categories and subcategories in session for use in FilterProducts.php
$_SESSION['categories'] = $categories;
$_SESSION['subcategories'] = $subcategories;

// // Render the home template
// echo $twig->render('home.twig', [
//   'user' => $_SESSION['user'],
//   'categories' => $categories,
//   'subcategories' => $subcategories,
//   'products' => $products,
//   'currentPage' => $page,
//   'totalPages' => $totalPages,
// ]);

if (isset($_SESSION['user']) && $_SESSION['role'] == "customer") {
  $user = $_SESSION['user'];
  if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
    echo $twig->render('home.twig', [
      'user' => $user,
      'categories' => $categories,
      'subcategories' => $subcategories,
      'products' => $products,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'message' => $message,

    ]);
  } else {
    echo $twig->render('home.twig', [
      'user' => $user,
      'categories' => $categories,
      'subcategories' => $subcategories,
      'products' => $products,
      'currentPage' => $page,
      'totalPages' => $totalPages,        

    ]);
  }
} else {
  echo $twig->render('home.twig', [
    'categories' => $categories,
    'subcategories' => $subcategories,
    'products' => $products,
    'currentPage' => $page,
    'totalPages' => $totalPages,
  ]);
}
