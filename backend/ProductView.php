<?php
session_start();

include_once('TwigConfig.php');
include_once('config/config.php');


$pdo = new PDO($dsn, $username, $password, $options);

$reviewable = 0;

$err = [];
//Adding Reviews
if (isset($_POST['submitReview'])) {

  if (empty($_POST['rating'])) {
    $err['rating'] = "Please Rate by clicking on Star";
  }
  if (empty($_POST['review'])) {
    $err['review'] = "Please give a feedback";
  }
  $specialChars = '@!#$%^&*()-=+[{]};:\'"<>/?\\|';
  if (strpbrk($_POST['review'], $specialChars) !== false) {
    $err['review'] = "Feedback can contain only alpha numeric characters";
  }

  if (count($err) == 0) {

    $sql = "INSERT INTO reviews (product_id, user_id, rating, review) 
      VALUES (:product_id, :user_id, :rating, :review)";
    $stmt = $pdo->prepare($sql);

    $product_id = intval($_POST['product_id']);
    $user_id = $_POST['user_id'];
    $rating = $_POST['totalStars'];
    $reviews = $_POST['review'];
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review', $reviews);

    $result = $stmt->execute();
  }

  $id = $_POST['product_id'];

  $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
  $stmt->execute([
    'id' => $id,
  ]);

  $product = $stmt->fetch();

  $product_id = $product['id'];

  header("Location: ProductView.php?id=$product_id");
}



if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $pdo = new PDO($dsn, $username, $password, $options);

  $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
  $stmt->execute([
    'id' => $id,
  ]);

  $product = $stmt->fetch();

  $product_id = $product['id'];
}





if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  $userId = $user['id'];

  $stmt = $con->query("SELECT id FROM orders WHERE user_id = $userId");
  $orderIds = $stmt->fetch_all(MYSQLI_ASSOC);
  $orderIdsString = '';
  foreach ($orderIds as $order) {
    $orderIdsString .= $order['id'] . ',';
  }

  $orderIdsString = substr($orderIdsString, 0, strlen($orderIdsString) - 2);
  if (strlen($orderIdsString) > 0) {



    //$orderIdsString = implode(',', $orderIds[0]);
    $stmt = $con->query("SELECT product_id FROM order_items WHERE order_id IN ($orderIdsString) AND product_id NOT IN (SELECT product_id FROM reviews WHERE user_id = $userId)");
    $productIds = $stmt->fetch_all(MYSQLI_ASSOC);

    foreach ($productIds as $order) {
      if ($order['product_id'] == $product_id) {
        $reviewable = 1;
        break;
      }
    }
  }
}





$stmt = $con->query("SELECT * FROM categories");
$categories = $stmt->fetch_all(MYSQLI_ASSOC);

$stmt = $con->query("SELECT * FROM subcategories");
$subcategories = $stmt->fetch_all(MYSQLI_ASSOC);

$stmt = $con->query("SELECT * FROM reviews WHERE product_id =$id");
$reviews = $stmt->fetch_all(MYSQLI_ASSOC);

$stmt = $con->query("SELECT * FROM users");
$users = $stmt->fetch_all(MYSQLI_ASSOC);



if ($product) {
  $cat_id = $product['category_id'];
  $stmt = $con->query("SELECT * FROM categories WHERE id = $cat_id ");
  $category = $stmt->fetch_all(MYSQLI_ASSOC);

  $subcat_id = $product['category_id'];
  $stmt = $con->query("SELECT * FROM subcategories WHERE id=$subcat_id");
  $subcategory = $stmt->fetch_all(MYSQLI_ASSOC);
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    echo $twig->render('productView.twig', ['product' => $product, 'user' => $user, 'reviews' => $reviews, 'category' => $category, 'subcategory' => $subcategory, 'categories' => $categories, 'subcategories' => $subcategory, 'reviewable' => $reviewable, 'users' => $users]);
  } else {
    echo $twig->render('productView.twig', ['product' => $product, 'reviews' => $reviews, 'category' => $category, 'subcategory' => $subcategory, 'categories' => $categories, 'subcategories' => $subcategory, 'reviewable' => $reviewable, 'users' => $users]);
  }
}
