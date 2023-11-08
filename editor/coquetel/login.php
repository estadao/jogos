<?php

if ( isset( $_COOKIE[ 'user' ] ) ) {
  $_SESSION[ 'user' ] = $_COOKIE[ 'user' ];
  $_SESSION[ 'name' ] = $_COOKIE[ 'name' ];
}

session_start();

if ( isset( $_SESSION[ 'user' ] ) ) {
  header( 'location:./' );
}

$user = isset( $_POST[ 'user' ] ) ? trim( $_POST[ 'user' ] ) : '';
$pass = isset( $_POST[ 'pass' ] ) ? trim( $_POST[ 'pass' ] ) : '';
$remember = isset( $_POST[ 'remember' ] );

if ( $user && $pass ) {

  // print_r( '<pre>' );

  //extract data from the post
  //set POST variables
  // $url = 'http://172.22.64.17/auth/';
  $url = 'https://atelie.estadao.com.br/auth/';

  $fields = array(
    'user' => urlencode( $_POST[ 'user' ] ),
    'pass' => urlencode( $_POST[ 'pass' ] ),
  );

  //url-ify the data for the POST

  $fields_string = '';

  foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
  rtrim($fields_string, '&');

  // print_r( $fields_string );

  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, count($fields));
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

  //execute post
  $result = curl_exec($ch);

  // print_r( $result );

  // print_r( $result );

  //close connection
  curl_close($ch);

  $login = json_decode( $result, true );

  // print_r( $login[0][ 'givenname' ][0] );

  if ( $login ) {
    session_start();

    // $user_info = $active_directory->getUserInfo();

    $_SESSION[ 'user' ] = $user;
    $_SESSION[ 'name' ] = $login[0][ 'givenname' ][0];

    if ( $remember ) {
      setcookie( 'user', $_SESSION[ 'user' ], ( time() + ( 30 * 24 * 3600 ) ) );
      setcookie( 'name', $_SESSION[ 'name' ], ( time() + ( 30 * 24 * 3600 ) ) );
    }

    header( 'location:./' );
  }

  // print_r( '</pre>' );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/app.min.css">

    <title>Login – Coquetel</title>

    <style>
      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }
    </style>
  </head>
  <body>
    <form method="post">
      <div class="form-group">
        <label for="txt-user">Usuário</label>
        <input type="text" class="form-control" id="txt-user" name="user" required autofocus>
      </div>

      <div class="form-group">
        <label for="txt-pass">Senha</label>
        <input type="password" class="form-control" id="txt-pass" name="pass" required>
      </div>

      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="remember" checked>
          Lembrar-me
        </label>
      </div>

      <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
  </body>
</html>
