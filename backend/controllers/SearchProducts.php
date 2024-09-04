<?php

require '../config/config.php'; // Include your database configuration
require '../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../../views');
$twig = new \Twig\Environment($loader, [
  'cache' => false,
]);



$search = isset($_GET['search']) ? $_GET['search'] : '';
$pdo = new PDO($dsn, $username, $password, $options);
$query = "SELECT * FROM products WHERE name LIKE :search OR description LIKE :search LIMIT 12";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the HTML for the updated product catalog
if ($products) {
  echo $twig->render('Products.twig', [
    'categories' => $categories,
    'subcategories' => $subcategories,
    'products' => $products,
    'currentPage' => $page,
    'totalPages' => $totalPages,
  ]);
} else {
  echo '<p>No products found.</p>';
}
