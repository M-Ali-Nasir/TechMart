<?php
// controllers/FilterProducts.php

session_start();

require '../../vendor/autoload.php';

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);


$loader = new \Twig\Loader\FilesystemLoader('../../views');
$twig = new \Twig\Environment($loader, ['cache' => false]);

include_once('../config/config.php');

$pdo = new PDO($dsn, $username, $password, $options);

$categoryId = isset($_POST['category_id']) ? $_POST['category_id'] : 'all';
$subcategoryId = isset($_POST['subcategory_id']) ? $_POST['subcategory_id'] : null;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Set the number of items per page
$limit = 12;
$offset = ($page - 1) * $limit;


// Build the query
$query = "SELECT * FROM products WHERE 1=1";

// Add category filter
if ($categoryId !== "all") {
  $query .= " AND category_id = :category_id";
}

// Add subcategory filter
if ($subcategoryId !== "all") {
  $query .= " AND subcategory_id = :subcategory_id";
}

// Add search filter
if (!empty($search)) {
  $query .= " AND (name LIKE :search OR description LIKE :search)";
}

// Add pagination
$query .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);

// Bind parameters
if ($categoryId !== 'all') {
  $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
}
if ($subcategoryId !== "all") {
  $stmt->bindValue(':subcategory_id', $subcategoryId, PDO::PARAM_INT);
}
if (!empty($search)) {
  $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Calculate total number of products for pagination
$countQuery = "SELECT COUNT(*) FROM products WHERE 1=1";

if ($categoryId !== 'all') {
  $countQuery .= " AND category_id = :category_id";
}
if ($subcategoryId !== "all") {
  $countQuery .= " AND subcategory_id = :subcategory_id";
}
if (!empty($search)) {
  $countQuery .= " AND (name LIKE :search OR description LIKE :search)";
}

$countStmt = $pdo->prepare($countQuery);

if ($categoryId !== 'all') {
  $countStmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
}
if ($subcategoryId !== "all") {
  $countStmt->bindValue(':subcategory_id', $subcategoryId, PDO::PARAM_INT);
}
if (!empty($search)) {
  $countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

$countStmt->execute();
$totalProducts = $countStmt->fetchColumn();
$totalPages = ceil($totalProducts / $limit);

if (empty($redis->get('categories'))) {
  $stmt = $con->query("SELECT * FROM categories");
  $categories = $stmt->fetch_all(MYSQLI_ASSOC);
  $redis->setex("categories", 1200, serialize($categories));
} else {
  $categories = unserialize($redis->get("categories"));
}





if (empty($redis->get('subcategories'))) {
  $stmt = $con->query("SELECT * FROM subcategories");
  $subcategories = $stmt->fetch_all(MYSQLI_ASSOC);
  $redis->setex("subcategories", 1200, serialize($subcategories));
} else {
  $subcategories = unserialize($redis->get("subcategories"));
}

// Render the products.twig template with filtered products


if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
    echo $twig->render('products.twig', [
      'user' => $user,
      'categories' => $categories,
      'subcategories' => $subcategories,
      'products' => $products,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'message' => $message,
      'search' => $search,
    ]);
  } else {
    echo $twig->render('products.twig', [
      'user' => $user,
      'categories' => $categories,
      'subcategories' => $subcategories,
      'products' => $products,
      'currentPage' => $page,
      'totalPages' => $totalPages,
      'search' => $search,
    ]);
  }
} else {
  echo $twig->render('products.twig', [
    'categories' => $categories,
    'subcategories' => $subcategories,
    'products' => $products,
    'currentPage' => $page,
    'totalPages' => $totalPages,
    'search' => $search,
  ]);
}
