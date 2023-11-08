<?php

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
  $file = !empty($_FILES['pFile']) ? $_FILES['pFile'] : '';

  if (
    preg_match('#palavras-cruzadas|sudoku#', $game)
    &&
    preg_match('#^[0-9]{4}/[0-9]{2}/[0-9]{2}$#', $date)
    &&
    $text
    &&
    $file
  ) {
    $root = explode('/atelie/', __DIR__)[0];
    $path = "$root/arte/jogos/$game/src/data/$date";
    // $path = "$root/arte/jogos/$game/data/$date";

    if (count(glob($path, GLOB_ONLYDIR))) {
      $response->status = 409;
      $response->output = 'Diretório existente';
    } else {
      recursive_copy("templates/estadao/$game", $path);
      recursive_copy("templates/parceiro/$game", "$path/parceiro");

      // TODO: alterar nome do arquivo para algo que não use o nome do jogo
      $filename = $game === 'palavras-cruzadas' ? 'cruzada' : $game;

      // TODO: alterar pasta para `data`
      file_put_contents("$path/dados/$filename.txt", $text);
      file_put_contents("$path/parceiro/dados/$filename.txt", $text);

      // TODO: alterar pasta para `media/images` e converter para PNG-8
      move_uploaded_file($file['tmp_name'], "$path/images/$filename.jpg");
      copy("$path/images/$filename.jpg", "$path/parceiro/images/$filename.jpg");

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
