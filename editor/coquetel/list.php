<!DOCTYPE html>
<html>
  <head>
    <title>Sudoku: gerador</title>

    <meta name="viewport" content="width=device-width" />
    <meta charset="utf-8">

    <meta name="google" value="notranslate" />
    <meta name="google" content="notranslate" />
    <meta http-equiv="cleartype" content="on">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
    <meta name="google-signin-client_id" content="631143309273-seukh1aj36qfk4p7sbama3mut2a6f53g.apps.googleusercontent.com">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=1.0">

  </head>

  <body class="login-">
  	<div id="sideBar">
  		<!-- <div id="loginBt" class="g-signin2" data-onsuccess="onSignIn"></div> -->
	  	<!-- <button id="logoutBt">sair</button> -->
      <a href="logout" id="logoutBt">sair</a>
  	</div>

	  <!-- <div id="loginMsg"></div> -->

  	<div id="container" class="list-">
      <p class="topLinks">
        <a class="topLink" href="cruzada.php">cruzada</a>
        <a class="topLink" href="sudoku.php">sudoku</a>
        <a class="topLink active" href="list.php">publicados</a>
      </p>

      <div class="listRow header">
        <div class="col">data </div>
        <div class="col">cruzada </div>
        <div class="col">sudoku </div>
        <div class="col"></div>
      </div>

<?php
  date_default_timezone_set('UTC');

  $lastmonth = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
  $folders = glob('../*', GLOB_ONLYDIR);
  rsort($folders);
  $folders = array_slice($folders,0,40);

  foreach( $folders as $folder ) {
   if( filectime($folder) > $lastmonth ){
    if( preg_match('/^20[1-2][0-9]_[0-1][0-9]_../', basename($folder)) &&
        fileowner ( '../'.basename($folder) ) == 48 ){
      $folder_str = basename($folder);
      $dateStr = str_replace('_', '/', $folder_str);
echo <<<HTML
  <div class="listRow">
    <div class="col">$dateStr</div>
HTML;

      if( count( glob('../'.$folder_str.'/cruzada/'), GLOB_ONLYDIR ) > 0 ){
echo <<<HTML
      <div class="col"><a href="../$folder_str/cruzada/" target="_blank">sim</a></div>
HTML;
      }else if( count( glob('../'.$folder_str.'/crosswords/'), GLOB_ONLYDIR ) > 0){
echo <<<HTML
      <div class="col"><a href="../$folder_str/crosswords/" target="_blank">sim</a></div>
HTML;
      }else{
echo <<<HTML
      <div class="col">não</div>
HTML;
      }
      if( count( glob('../'.$folder_str.'/sudoku/', GLOB_ONLYDIR )) > 0 ){
echo <<<HTML
      <div class="col"><a href="../$folder_str/sudoku/" target="_blank">sim</a></div>
HTML;
      }else{
echo <<<HTML
        <div class="col">não</div>
HTML;
      }
echo <<<HTML
  </div>
HTML;

    }
   }
  }

?>
<!--
      <div class="listRow">
        <div class="col">25/01/2017</div>
        <div class="col">não</div>
        <div class="col"><a href="#">sim</a></div>
        <div class="col"><a href="#" class="delete">x</a></div>
      </div>

      <div class="listRow">
        <div class="col">24/01/2017</div>
        <div class="col"><a href="#">sim</a></div>
        <div class="col"><a href="#">sim</a></div>
        <div class="col"><a href="#" class="delete">x</a></div>
      </div>

      <div class="listRow">
        <div class="col">23/01/2017</div>
        <div class="col"><a href="#">sim</a></div>
        <div class="col">não </div>
        <div class="col"><a href="#" class="delete">x</a></div>
      </div>

      <div class="listRow">
        <div class="col">22/01/2017</div>
        <div class="col"><a href="#">sim</a></div>
        <div class="col"><a href="#">sim</a></div>
        <div class="col"><a href="#" class="delete">x</a></div>
      </div>
-->
    </div>


	</div>

    <button id="excludeBtn">excluir</button>
	  <script src="js/jquery-2.1.3.min.js"></script>
  	<script src="https://apis.google.com/js/platform.js" async defer></script>
  	<script src="js/main_list.js?v=1.0.1"></script>
  </body>
</html>
<?php
/*
function removeDirectory($directory){
    foreach(glob("{$directory}/*") as $file){
        if(is_dir($file)) {
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}
*/
?>
