<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
  require '../../vendor/autoload.php';

  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);

  include_once('../config/config.php');


  $stmt = $con->query("SELECT * FROM categories");
  $categories = $stmt->fetch_all(MYSQLI_ASSOC);

  echo $twig->render('admin/categories.twig', ['categories' => $categories]);
} else {
  header("Location: ../Login.php");
  exit();
}
