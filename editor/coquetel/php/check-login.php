<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


if( isset($_POST['email']) ){

  $allowed_emails = array(
    // 0 => "fabio.tdz@gmail.com",
    // 1 => "carolinex@gmail.com",
    0 => "arte.estado@gmail.com",
    1 => "nac.redacao@gmail.com",
    2 => "nacredacao@gmail.com",
    3 => "estadao.nac@gmail.com",
    4 => "estadaonac@gmail.com",
    // 7 => "fontespereira88@gmail.com"
  );

  if (in_array($_POST['email'], $allowed_emails)) {
    $_SESSION["user"] = $_POST['email'];
    echo json_encode("allowed");
  }else{
    session_destroy();
    echo json_encode("not allowed");
  }

}else{
  echo json_encode("no email provided");
  session_destroy();
}

?>
