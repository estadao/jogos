<?php

class HZip
{
  /**
   * Add files and sub-directories in a folder to zip file.
   * @param string $folder
   * @param ZipArchive $zipFile
   * @param int $exclusiveLength Number of text to be exclusived from the file path.
   */
  private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
    $handle = opendir($folder);
    while (false !== $f = readdir($handle)) {
      if ($f != '.' && $f != '..') {
        $filePath = "$folder/$f";
        // Remove prefix from file path before add to zip.
        $localPath = substr($filePath, $exclusiveLength);
        if (is_file($filePath)) {
          $zipFile->addFile($filePath, $localPath);
        } elseif (is_dir($filePath)) {
          // Add sub-directory.
          $zipFile->addEmptyDir($localPath);
          self::folderToZip($filePath, $zipFile, $exclusiveLength);
        }
      }
    }
    closedir($handle);
  }

  /**
   * Zip a folder (include itself).
   * Usage:
   *   HZip::zipDir('/path/to/sourceDir', '/path/to/out.zip');
   *
   * @param string $sourcePath Path of directory to be zip.
   * @param string $outZipPath Path of output zip file.
   */
  public static function zipDir($sourcePath, $outZipPath)
  {
    $pathInfo = pathInfo($sourcePath);
    $parentPath = $pathInfo['dirname'];
    $dirName = $pathInfo['basename'];

    $z = new ZipArchive();
    $z->open($outZipPath, ZIPARCHIVE::CREATE);
    // $z->addEmptyDir($dirName);
    // self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
    self::folderToZip($sourcePath, $z, strlen("$sourcePath/"));
    $z->close();
  }
}


function create_zip($source_path, $destination_path, $game, $date) {
  // $zip = new ZipArchive();
	// $zip->open("$destination_path/$game-$date.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
  //
  // $zip->addFile("$source_path/index.html", "$game-$date/index.html");
  //
  // $zip->close();

  [$year, $month, $day] = explode('-', $date);

  $zip_file = "$destination_path/{$game}_{$year}_{$month}_{$day}.zip";

  HZip::zipDir($source_path, $zip_file);

  return $zip_file;
}

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
    preg_match('#^[0-9]{4}-[0-9]{2}-[0-9]{2}$#', $date)
    &&
    $text
    &&
    $file
  ) {
    [$year, $month, $day] = explode('-', $date);

    $root_path = explode('/atelie/', __DIR__)[0] . '/atelie/public';
    // $estadao_path = "$root/arte/jogos/$game/data/$date";
    $estadao_path = "$root_path/jogos/$game/$year/$month/$day";
    $partner_path = "$root_path/parceiros/jogos/coquetel/$game/$year/$month/$day";

    // if (count(glob($estadao_path, GLOB_ONLYDIR))) {
    //   $response->status = 409;
    //   $response->output = 'Diretório existente';
    // } else {
      mkdir($estadao_path, 0777, TRUE);
      mkdir($partner_path, 0777, TRUE);

      // $temp_path = tempnam(sys_get_temp_dir(), "$game-$date");

      // unlink($temp_path);

      // $temp_path = sys_get_temp_dir() . "/$game-$date";
      $temp_path = sys_get_temp_dir() . "/{$game}_{$year}_{$month}_{$day}";

      recursive_copy("templates/parceiros/$game", "$temp_path");

      // TODO: alterar nome do arquivo para algo que não use o nome do jogo
      $filename = $game === 'palavras-cruzadas' ? 'cruzada' : $game;

      file_put_contents("$estadao_path/solution.txt", $text);
      // file_put_contents("$estadao_path/parceiro/dados/$filename.txt", $text);
      // copy("$estadao_path/solution.txt", "$partner_path/solution.txt");
      // copy("$estadao_path/solution.txt", "$partner_path/dados/$filename.txt");
      copy("$estadao_path/solution.txt", "$temp_path/dados/$filename.txt");

      // TODO: converter para PNG-8
      move_uploaded_file($file['tmp_name'], "$estadao_path/puzzle.jpg");
      // copy("$estadao_path/puzzle.jpg", "$partner_path/puzzle.jpg");
      // copy("$estadao_path/puzzle.jpg", "$partner_path/images/$filename.jpg");
      copy("$estadao_path/puzzle.jpg", "$temp_path/images/$filename.jpg");

      $response->status = 200;
      // $response->output = 'OK';
      $response->output = create_zip($temp_path, $partner_path, $game, $date);
    // }
  } else {
    $response->status = 400;
    $response->output = 'Dados inválidos';
  }
}

echo json_encode($response);

exit;
