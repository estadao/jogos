<?php

date_default_timezone_set('America/Sao_Paulo');

session_start();

if (!empty($_COOKIE['user'])) {
  $_SESSION['user'] = $_COOKIE['user'];
  $_SESSION['name'] = $_COOKIE['name'];
}

$user = !empty($_SESSION['user']) ? $_SESSION['user'] : '';
$name = !empty($_SESSION['name']) ? $_SESSION['name'] : '';

if (!$user) {
  header('Location: login');

  exit;
}

?>

<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/app.min.css">

    <title><?= $page_title ?> – Coquetel</title>
  </head>

  <body>
    <div id="sideBar">
      <a href="logout" id="logoutBt">sair</a>
    </div>
