<?php

include_once('TwigConfig.php');
session_start();

if (isset($_SESSION['user'])) {
  header("Location: Home.php");
}
if (!(isset($_SESSION['message']))) {
  if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    echo $twig->render('auth/login.twig', ['errors' => $error]);
  } else {
    echo $twig->render('auth/login.twig');
  }
} else {
  $message = $_SESSION['message'];

  unset($_SESSION['message']);

  echo $twig->render('auth/login.twig', ['message' => $message]);
}
