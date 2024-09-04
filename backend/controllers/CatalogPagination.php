<?php
// Assuming you have a database connection
session_start();

require '../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../../views');
$twig = new \Twig\Environment($loader, [
  'cache' => false,
]);

include_once('../config/config.php');


$pdo = new PDO($dsn, $username, $password, $options);

$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : 'all';
$subcategory_id = isset($_POST['subcategory_id']) ? $_POST['subcategory_id'] : null;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = 12;  // Number of products per page
$offset = ($page - 1) * $limit;

// Build the query
$query = "SELECT * FROM products WHERE 1=1";

if ($category_id != 'all') {
  $query .= " AND category_id = :category_id";
}

if ($subcategory_id) {
  $query .= " AND subcategory_id = :subcategory_id";
}

$query .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);

if ($category_id != 'all') {
  $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
}

if ($subcategory_id) {
  $stmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
}

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total products for pagination
$countQuery = "SELECT COUNT(*) as total FROM products WHERE 1=1";

if ($category_id != 'all') {
  $countQuery .= " AND category_id = :category_id";
}

if ($subcategory_id) {
  $countQuery .= " AND subcategory_id = :subcategory_id";
}

$countStmt = $pdo->prepare($countQuery);

if ($category_id != 'all') {
  $countStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
}

if ($subcategory_id) {
  $countStmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
}

$countStmt->execute();
$totalProducts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalProducts / $limit);



$stmt = $con->query("SELECT * FROM categories");
$categories = $stmt->fetch_all(MYSQLI_ASSOC);

$stmt = $con->query("SELECT * FROM subcategories");
$subcategories = $stmt->fetch_all(MYSQLI_ASSOC);
// Render the products using Twig or directly output HTML

echo $twig->render('Products.twig', [
  'categories' => $categories,
  'subcategories' => $subcategories,
  'products' => $products,
  'currentPage' => $page,
  'totalPages' => $totalPages,
]);
