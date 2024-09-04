<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
  require '../../vendor/autoload.php';

  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);
  $redis = new Redis();
  $redis->connect('127.0.0.1', 6379);

  include_once('../config/config.php');



  if (!($redis->get('customers'))) {
    $stmt = $con->query("SELECT * FROM users WHERE role='customer'");
    $customers = $stmt->fetch_all(MYSQLI_ASSOC);
    $redis->setex("customers", 1200, serialize($customers));
  } else {
    $customers = unserialize($redis->get("customers"));
  }

  echo $twig->render('admin/customers.twig', ['customers' => $customers]);
} else {
  header("Location: ../Login.php");
  exit();
}
