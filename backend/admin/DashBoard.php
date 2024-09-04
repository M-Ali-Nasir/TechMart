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

  if (!($redis->get('totalOrders'))) {
    $stmt = $con->query("SELECT COUNT(*) FROM orders");
    $totalOrders = $stmt->fetch_all(MYSQLI_ASSOC);
    $totalOrders = $totalOrders[0]['COUNT(*)'];
    $redis->setex("totalOrders", 1200, serialize($totalOrders));
  } else {
    $totalOrders = unserialize($redis->get("totalOrders"));
  }

  if (!($redis->get('pendingOrders'))) {
    $stmt = $con->query("SELECT COUNT(*) FROM orders WHERE status ='pending'");
    $pendingOrders = $stmt->fetch_all(MYSQLI_ASSOC);
    $pendingOrders = $pendingOrders[0]['COUNT(*)'];
    $redis->setex("pendingOrders", 1200, serialize($pendingOrders));
  } else {
    $pendingOrders = unserialize($redis->get("pendingOrders"));
  }


  if (!($redis->get('deliveredOrders'))) {
    $stmt = $con->query("SELECT COUNT(*) FROM orders WHERE status='delivered'");
    $deliveredOrders = $stmt->fetch_all(MYSQLI_ASSOC);
    $deliveredOrders = $deliveredOrders[0]['COUNT(*)'];
    $redis->setex("deliveredOrders", 1200, serialize($deliveredOrders));
  } else {
    $deliveredOrders = unserialize($redis->get("deliveredOrders"));
  }

  if (!($redis->get('cancelledOrders'))) {
    $stmt = $con->query("SELECT COUNT(*) FROM orders WHERE status='cancelled'");
    $cancelledOrders = $stmt->fetch_all(MYSQLI_ASSOC);
    $cancelledOrders = $cancelledOrders[0]['COUNT(*)'];
    $redis->setex("cancelledOrders", 1200, serialize($cancelledOrders));
  } else {
    $cancelledOrders = unserialize($redis->get("cancelledOrders"));
  }

  if (!($redis->get('totalProducts'))) {
    $stmt = $con->query("SELECT COUNT(*) FROM products");
    $totalProducts = $stmt->fetch_all(MYSQLI_ASSOC);
    $totalProducts = $totalProducts[0]['COUNT(*)'];
    $redis->setex("totalProducts", 1200, serialize($totalProducts));
  } else {
    $totalProducts = unserialize($redis->get("totalProducts"));
  }


  echo $twig->render('admin/dashboard.twig', ['totalOrders' => $totalOrders, 'pendingOrders' => $pendingOrders, 'deliveredOrders' => $deliveredOrders, 'cancelledOrders' => $cancelledOrders, 'totalProducts' => $totalProducts]);
} else {
  header("Location: ../Login.php");
  exit();
}
