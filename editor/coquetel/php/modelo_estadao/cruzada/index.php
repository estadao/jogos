<? define( 'ROOT_PATH', preg_replace( '/infograficos.*$/', 'infograficos/', dirname( __FILE__ ) ) ) ?>
<?php
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

    <title>Cruzadas</title>

    <meta name="author" content="Estadão" />
    <meta name="description" content="Jogue a cruzada do dia no desktop ou no seu dispositivo móvel" />

    <meta property="fb:app_id" content="268068763366373" />
    <meta property="og:title" content="Cruzadas - Estadão" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="Jogue a cruzada do dia no desktop ou em seu dispositivo móvel" />
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:site_name" content="Estadão" />
    <meta property="og:url" content=<? echo '"https://'.getCurrDir().'"' ?> />
    <meta property="og:image" content=<? echo '"https://'.getCurrDir().'images/cruzada.jpg"'?> />
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
    <link rel="stylesheet" href="css/cruzada.css?v=<?php echo cache_buster(); ?>">

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
        <h1 class="alert">Palavras cruzadas</h1>

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
            <!-- Conteúdo que deve ser substituído pela janela do Paywall -->

            <div id="page" class="site">
              <div id="pubBanner">
                <!-- Banner de Publicidade 970x90 -->
                <div id="OAS_Position1"></div>
              </div>

              <div id="puzzleContainer">

                <div id="puzzleHolder">
                  <div id="puzzleAnswers"></div>
                  <div id="puzzleInputs"></div>
                  <img class="crosswords" src="images/cruzada.jpg" />
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

            <div id="keyboardContainer" class="open">
                <ul id="keyboard" class="unselectable">
                    <li class="key lq"><span>q</span></li>
                    <li class="key lw"><span>w</span></li>
                    <li class="key le"><span>e</span></li>
                    <li class="key lr"><span>r</span></li>
                    <li class="key lt"><span>t</span></li>
                    <li class="key ly"><span>y</span></li>
                    <li class="key lu"><span>u</span></li>
                    <li class="key li"><span>i</span></li>
                    <li class="key lo"><span>o</span></li>
                    <li class="key lp"><span>p</span></li>
                    <li class="spacer sp1"><span></span></li>
                    <li class="key la"><span>a</span></li>
                    <li class="key ls"><span>s</span></li>
                    <li class="key ld"><span>d</span></li>
                    <li class="key lf"><span>f</span></li>
                    <li class="key lg"><span>g</span></li>
                    <li class="key lh"><span>h</span></li>
                    <li class="key lj"><span>j</span></li>
                    <li class="key lk"><span>k</span></li>
                    <li class="key ll"><span>l</span></li>
                    <li class="spacer sp2"><span></span></li>
                    <li class="hide sp3"><span></span></li>
                    <li class="key lz"><span>z</span></li>
                    <li class="key lx"><span>x</span></li>
                    <li class="key lc"><span>c</span></li>
                    <li class="key lv"><span>v</span></li>
                    <li class="key lb"><span>b</span></li>
                    <li class="key ln"><span>n</span></li>
                    <li class="key lm"><span>m</span></li>
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
    <script src="js/cruzada.js?v=<?php echo cache_buster(); ?>"></script>

    <? include_once ROOT_PATH . 'core/body-outro.php' ?>

	</body>
</html>
