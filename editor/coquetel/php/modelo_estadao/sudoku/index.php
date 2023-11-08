<?

define( 'ROOT_PATH', preg_replace( '/infograficos.*$/', 'infograficos/', dirname( __FILE__ ) ) );

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

function getCurrDir(){
  $url = $_SERVER['REQUEST_URI'];
  $parts = explode('/',$url);
  $dir = $_SERVER['SERVER_NAME'];
  for ($i = 0; $i < count($parts) - 1; $i++) {
   $dir .= $parts[$i] . "/";
  }
  return $dir;
}
function cache_buster(){
  return time();
}
function getCurrGuid(){
  $dir   = getCurrDir();
  $parts = explode('/jogos/', $dir );
  $guid  = rtrim($parts[ 1 ], '/');
  $guid  = str_replace('/', '-', $guid);
  return $guid;
}
?>
<!DOCTYPE html>
<html>
  <head>

    <? include_once ROOT_PATH . 'core/head-intro.php' ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no" />

    <title>Sudoku</title>

    <meta name="author" content="Estadão" />
    <meta name="description" content="Jogue a cruzada do dia no desktop ou no seu dispositivo móvel" />

    <meta property="fb:app_id" content="268068763366373" />
    <meta property="og:title" content="Sudoku - Estadão" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="Jogue o sudoku do dia no desktop ou em seu dispositivo móvel" />
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:site_name" content="Estadão" />
    <meta property="og:url" content=<? echo '"https://'.getCurrDir().'"' ?> />
    <meta property="og:image" content=<? echo '"https://'.getCurrDir().'images/sudoku.jpg"'?> />
    <meta property="og:image:type" content="image/jpeg" />

    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="theme-color" content="#ffffff" />

    <link rel="shortcut icon" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/favicon.ico" />

    <link rel="apple-touch-icon" sizes="57x57"   href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-57x57.png"   />
    <link rel="apple-touch-icon" sizes="60x60"   href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-60x60.png"   />
    <link rel="apple-touch-icon" sizes="72x72"   href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-72x72.png"   />
    <link rel="apple-touch-icon" sizes="76x76"   href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-76x76.png"   />
    <link rel="apple-touch-icon" sizes="114x114" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/apple-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/android-icon-192x192.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/favicon-16x16.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="https://infograficos.estadao.com.br/geral/_assets/image/favicons/favicon-96x96.png" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/sudoku.css?v=<?php echo cache_buster(); ?>">

    <!-- Paywall (1/2) -->
    <meta name="pw-config" id="pw-config" data-tipo="Especiais" data-produto="9b29988a" data-domain="https://acesso.estadao.com.br/paywall/" />
    
    <? include_once ROOT_PATH . 'core/head-outro.php' ?>

  </head>

  <body>
    
    <? include_once ROOT_PATH . 'core/body-intro.php' ?>

    <script type="text/javascript" src="https://www.estadao.com.br/estadao/barra-parceiros/barra-de-parceiros-responsiva.js"></script>

    <div class="title">
      <div id="top-bar">
        <a href="https://www.estadao.com.br/" class="oesp" target="_blank"><span>Estadão</span></a>

        <div class="sharebar">
          <ul>
            <li><a id="share-facebook" href="javascript:;"><i class="fa fa-facebook"></i></a></li>
            <li><a id="share-twitter" href="javascript:;"><i class="fa fa-twitter"></i></a></li>
            <li><a id="more-btn" href="javascript:;"><i class="fa fa-calendar"></i></a></li>
          </ul>
        </div>

        <div id="calendar" class="unselectable"></div>
      </div>

      <div class="intro">
        <h1 class="alert">Sudoku</h1>

        <div class="topBt">
            <input type="button" id="btCheck" name="check" value="conferir" />
            <input type="button" id="btClear" name="clear" value="limpar" />
            <input type="button" id="btZoom" name="zoom" value="" />
            <div id="timeCounter">Tempo<br><span>00:00:00</span></div>
        </div>
      </div>

      <div id="alertBox">
          <div id="alertContent">
          Toque uma vez para preencher na horizontal, duas vezes para preencher na vertical
          </div>
      </div>
    </div>

    <!-- Paywall começa -->
    <div class="pw-container" id="pw-<?= getCurrGuid() ?>">
      <div class="pw-container" id="sw-<?= getCurrGuid() ?>">

        <div id="page" class="site">
          <div id="pubBanner">
            <!-- Banner de Publicidade 970x90 -->
            <div id="OAS_Position1"></div>
          </div>

          <div id="puzzleContainer">
            <div id="puzzleHolder">
              <div id="puzzleAnswers"></div>
              <div id="puzzleInputs"></div>
              <img class="crosswords" src="images/sudoku.jpg" />

              <div id="copyright">
                <span class="logoCopy">
                  <img src="images/logo-coquetel.jpg" alt="© COQUETEL" />
                </span>
                <span class="dev">© Revistas COQUETEL / Desenvolvimento: ESTADÃO
                </span>
              </div>
            </div>
          </div>

        </div>

        <div id="keyboardContainer">
          <ul id="keyboard">
              <li class="key l1"><span>1</span></li>
              <li class="key l2"><span>2</span></li>
              <li class="key l3"><span>3</span></li>
              <li class="key l4"><span>4</span></li>
              <li class="key l5"><span>5</span></li>
              <li class="key l6"><span>6</span></li>
              <li class="hide"><span></span></li>
              <li class="key l7"><span>7</span></li>
              <li class="key l8"><span>8</span></li>
              <li class="key l9"><span>9</span></li>
              <li class="delete"><span></span></li>
          </ul>
        </div>

        <div id="horizOverlay">
          <p>Para jogar, coloque o aparelho na vertical</p>
        </div>
      </div>
    </div>
    <!-- Paywall termina -->

    <!-- Paywall (2/2) -->
    <script src="https://acesso.estadao.com.br/paywall/pw.js"></script>

    <script src="js/jquery-3.1.0.min.js"></script>
    <script src="js/calendar.js?v=<?php echo cache_buster(); ?>"></script>
    <script src="js/sudoku.js?v=<?php echo cache_buster(); ?>"></script>

    <? include_once ROOT_PATH . 'core/body-outro.php' ?>

	</body>
</html>
