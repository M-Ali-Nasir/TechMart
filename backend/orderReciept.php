<?php
session_start();
require '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, ['cache' => false]);

include_once('C:\xampp\htdocs\TechMart\backend\config\config.php');






$stripe = new \Stripe\StripeClient('sk_test_51PG1kF2LReVvpXJDMJiMtdhtY2Q0HG3QTnbD0szlNRPjOgVLjQEUUoLdxWT7R8nCy6nKmUbpAmAsAnsUp8Osw9RX001s3KbKls');

$pdo = new PDO($dsn, $username, $password, $options);

if (isset($_GET['session_id'])) {



  if ($_SESSION['user']) {
    $user = $_SESSION['user'];
    $userId = $user['id'];
  }





  $sessionId = $_GET['session_id'];

  $response = $stripe->checkout->sessions->retrieve($sessionId);

  //Saving the Order Informations

  $stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id = :user_id');
  $stmt->execute(['user_id' => $userId]);
  $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $order_id = 0;

  if (count($cart) != 0) {

    $total_amount = $response->amount_total / 100;
    $sql = "INSERT INTO orders (user_id, total, status) VALUES (:user_id, :total_amount, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $userId, 'total_amount' => $total_amount, 'status' => 'pending']);


    $order_id = $pdo->lastInsertId();




    foreach ($cart as $item) {
      $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['order_id' => $order_id, 'product_id' => $item['product_id'], 'quantity' => $item['quantity']]);
    }

    if (isset($_SESSION['customerName']) && isset($_SESSION['customerAddress']) && isset($_SESSION['customerZipcode']) && isset($_SESSION['customerPhone'])) {
      $name = $_SESSION['customerName'];
      $address = $_SESSION['customerAddress'];
      $zipcode = $_SESSION['customerZipcode'];
      $phone = $_SESSION['customerPhone'];
      unset($_SESSION['customerName']);
      unset($_SESSION['customerAddress']);
      unset($_SESSION['customerZipcode']);
      unset($_SESSION['customerPhone']);

      $sql = "INSERT INTO order_address (order_id, name, address, zip_code, phone) VALUES (:order_id, :name, :address, :zipcode, :phone)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['order_id' => $order_id, 'name' => $name, 'address' => $address, 'zipcode' => $zipcode, 'phone' => $phone]);
    }
  }





  $query = "DELETE FROM cart WHERE user_id = :customer_id";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':customer_id', $userId, PDO::PARAM_INT);
  $stmt->execute();


  $stmt = $con->query("SELECT * FROM orders WHERE id = $order_id");
  $order = $stmt->fetch_all(MYSQLI_ASSOC);
  $stmt = $con->query("SELECT * FROM order_items WHERE order_id = $order_id");
  $order_items = $stmt->fetch_all(MYSQLI_ASSOC);
  $stmt = $con->query("SELECT * FROM products");
  $products = $stmt->fetch_all(MYSQLI_ASSOC);


  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    echo $twig->render('orderReciept.twig', [
      'user' => $user,
      'response' => $response,
    ]);
  } else {
    echo $twig->render('orderReciept.twig', ['response' => $response]);
  }
} else {
  header("Location: Home.php");
}
