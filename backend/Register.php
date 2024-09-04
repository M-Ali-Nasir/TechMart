<?php

include_once('TwigConfig.php');
include_once('config/config.php');
session_start();


if (!(isset($_SESSION['error']))) {
  echo $twig->render('auth/register.twig');
} else {
  $error = $_SESSION['error'];

  unset($_SESSION['error']);

  echo $twig->render('auth/register.twig', ['errors' => $error]);
}
