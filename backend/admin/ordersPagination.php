<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['role'] == 'admin') {

  require '../../vendor/autoload.php';

  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);

  include_once('../config/config.php');

  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);

  $pdo = new PDO($dsn, $username, $password, $options);


  if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $sql = "UPDATE orders 
    SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('status', $status);
    $stmt->bindParam('id', $id);

    $stmt->execute();
  }


  if (isset($_POST['page'])) {
    $page = $_POST['page'];
  } else {
    $page = 1;
  }

  $limit = 12;
  $offset = ($page - 1) * $limit;



  $stmt = $con->query("SELECT * FROM orders LIMIT $limit OFFSET $offset");
  $orders = $stmt->fetch_all(MYSQLI_ASSOC);

  if (!($redis->get('users'))) {
    $stmt = $con->query("SELECT * FROM users");
    $users = $stmt->fetch_all(MYSQLI_ASSOC);
    $redis->setex("users", 1200, serialize($users));
  } else {
    $users = unserialize($redis->get("users"));
  }

  $stmt = $con->query("SELECT COUNT(*) FROM orders");
  $totalorders = $stmt->fetch_assoc();
  $totalorders = $totalorders['COUNT(*)'];

  $totalPages = ceil($totalorders / $limit);

  $response = [
    'orders' => $orders,
    'users' => $users,
    'currentPage' => $page,
    'totalPages' => $totalPages,
  ];


  echo json_encode($response);
}
