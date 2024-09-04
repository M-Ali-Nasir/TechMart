<?php

session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
  require '../../vendor/autoload.php';

  $loader = new \Twig\Loader\FilesystemLoader('../../views');
  $twig = new \Twig\Environment($loader, [
    'cache' => false,
  ]);

  include_once('../config/config.php');


  $stmt = $con->query("SELECT * FROM subcategories");
  $subcategories = $stmt->fetch_all(MYSQLI_ASSOC);

  $stmt = $con->query("SELECT * FROM categories");
  $categories = $stmt->fetch_all(MYSQLI_ASSOC);

  echo $twig->render('admin/subcategories.twig', ['categories' => $categories, 'subcategories' => $subcategories]);
} else {
  header("Location: ../Login.php");
  exit();
}
