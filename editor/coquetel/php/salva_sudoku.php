<?php

// define('ENV', 'test');
//
// $root_path = [
//   'test' => '/Applications/MAMP/htdocs',
//   'live' => '/OESP/prod/www',
// ];

$root_path = '/var/www/html';

function recursive_copy($source, $dest) {
  if(is_dir($source)) {
    $dir_handle = opendir($source);

    while ($file = readdir($dir_handle)) {
      if ($file !== '.' && $file !== '..') {
        if (is_dir($source . '/' . $file)) {
          if (!is_dir($dest . '/' . $file)) {
            mkdir($dest . '/' . $file, 0777, TRUE);
          }

          recursive_copy($source . '/' . $file, $dest . '/' . $file);
        } else {
          copy($source . '/' . $file, $dest . '/' . $file);
        }
      }
    }

    closedir($dir_handle);
  } else {
    copy($source, $dest);
  }
}

$response = (object) [];

session_start();

if (!empty($_COOKIE['user'])) {
  $_SESSION['user'] = $_COOKIE['user'];
  $_SESSION['name'] = $_COOKIE['name'];
}

$user = !empty($_SESSION['user']) ? $_SESSION['user'] : '';
$name = !empty($_SESSION['name']) ? $_SESSION['name'] : '';

if (!($user && $name)) {
  $response->status = 404;
  $response->output = 'Usuário não encontrado';
} else {
  $game = !empty($_POST['game']) ? $_POST['game'] : '';
  $date = !empty($_POST['pDir']) ? $_POST['pDir'] : '';
  $text = !empty($_POST['pText']) ? $_POST['pText'] : '';

  if (
    preg_match('#palavras-cruzadas|sudoku#', $game)
    &&
    preg_match('#^[0-9]{4}/[0-9]{2}/[0-9]{2}$#', $date)
    &&
    $text
  ) {
    // $path = "{$root_path[ENV]}/arte/jogos/$game/$date";
    $path = "$root_path/arte/jogos/$game/$date";

    if (count(glob($path, GLOB_ONLYDIR))) {
      $response->status = 409;
      $response->output = 'Diretório existente';
    } else {
      // mkdir($path, 0777, TRUE);
      recursive_copy("templates/estadao/$game", $path);

      file_put_contents("$path/data/answer.txt", $text);

      $response->status = 200;
      $response->output = 'OK';
    }
  } else {
    $response->status = 400;
    $response->output = 'Dados inválidos';
  }
}

echo json_encode($response);

exit;
