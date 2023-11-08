<?php

session_start();

if ( isset( $_COOKIE[ 'user' ] ) ) {
  $_SESSION[ 'user' ] = $_COOKIE[ 'user' ];
  $_SESSION[ 'name' ] = $_COOKIE[ 'name' ];
}

$user = isset( $_SESSION[ 'user' ] ) ? $_SESSION[ 'user' ] : '';
$name = isset( $_SESSION[ 'name' ] ) ? $_SESSION[ 'name' ] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <style>
      body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }
    </style>
  </head>
  <body>
  <?php if ( $user ) : ?>
    <h3>Olá, <?= $name ?></h3>
    <a href="logout.php" class="btn btn-danger">Sair</a>
  <?php else : ?>
    <h3>Olá, visitante</h3>
    <a href="login.php" class="btn btn-success">Entrar</a>
  <?php endif ?>
  </body>
</html>
