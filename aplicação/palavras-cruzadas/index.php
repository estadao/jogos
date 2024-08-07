<?php

date_default_timezone_set('America/Sao_Paulo');

// Redireciona para a mais recente
// TODO: corrigir problema caso a do dia não for a mais recente
function redirect_last() {
  $iso_date = date('Y-m-d');

  header("Location: ./?d=$iso_date");

  exit;
}

// Obtém o parâmetro de data da URL
$iso_date = !empty($_GET['d']) ? $_GET['d'] : '';

// Verifica se a data é válida
if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $iso_date)) {
  $date = explode('-', $iso_date);

  $year = $date[0];
  $month = $date[1];
  $day = $date[2];

  $current = mktime(0, 0, 0, $month, $day, $year);

  // Verifica se a data está entre primeira e a do tidy_diagnose
  $first = mktime(0, 0, 0, 6, 6, 2016);
  $last = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

  // if ($current < $first || $current > $last) {
  //   redirect_last();
  // }

  // Verifica se existe quebra-cabeça publicado nesta data
  $root_path = explode('/arte/', __DIR__)[0] . '/arte';

  $url = '/public/jogos/palavras-cruzadas/' . date('Y/m/d', $current);
  $path = $root_path . $url;

  if (!count(glob($path, GLOB_ONLYDIR))) {
    redirect_last();
  }
} else {
  redirect_last();
}

?>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/include/core.php' ?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <?php $core->include('head-opening') ?>

    <meta charset="utf-8">
    <meta name="viewport" content="<?= $core->viewport ?>">

    <link rel="stylesheet" href="/share/styles/fonts.min.css">
    <link rel="stylesheet" href="/share/styles/reset.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/app.min.css?v=<?= $app->version ?>">

    <title><?= $app->title ?> – Estadão</title>

    <?php $core->include('seo') ?>
    <?php $core->include('sharing') ?>
    <?php $core->include('favicon') ?>

    <script>
      const path = '<?= $url ?>';
    </script>

    <?php $core->include('head-closing') ?>
  </head>

  <body>
    <?php $core->include('body-opening') ?>

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

    <?php $id = base64_encode(substr(hash('sha256', "arte/{$app->section}/{$app->category}/{$app->slug}"), 0, 8)) ?>

    <!-- Paywall começa -->
     <div class="pw-container" data-acesso="0" id="pw-MM_AG_PT_ASSET_<?= $id ?>" data-paywall-wrapper>
      <div class="pw-container" id="sw-MM_AG_PT_ASSET_<?= $id ?>">

            <div id="page" class="site">
             <div id="pubBanner">
                <!-- Banner de Publicidade 970x90 -->
                <div id="OAS_Position1"></div>
              </div>

              <div id="puzzleContainer">

                <div id="puzzleHolder">
                  <div id="puzzleAnswers"></div>
                  <div id="puzzleInputs"></div>
                  <img class="crosswords" src="<?= $url ?>/puzzle.jpg" />
                  <div id="copyright">
                    <span class="logoCopy">
                      <img src="media/images/logo-coquetel.jpg" alt="© COQUETEL" />
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

      <!-- Novo script de paywall -->
      <script>
            var params = {
              categoriaSeo: "",
              tipoAcesso: "0", 
              //  infiniteNewsClass: ".container-proximas-materias",
                midia: "noticia",
                datalayer: "dataLayerEstadao",
                domain: "https://arte.estadao.com.br/",
                coluna: "",
                noticiaWrappperClass: "",
                zephrArc: false,
                id: "",
            };
        //    dataLayerEstadao[0].pagina['blog'] = ""
            var loadPwz = ()=>{
                window.pwz(params)
            };
            
    const s = document.createElement('script')
    s.setAttribute('src', 'https://acesso.estadao.com.br/paywall/v2/paywallZephr/dist/pwz.js')
    s.addEventListener('load', () => {
      window.pwz(params)
    })
    document.head.append(s)
        </script>
 <!-- Paywall termina -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=URL" crossorigin="anonymous"></script>
    <script src="scripts/calendar.js?v=<?php $app->version ?>"></script>
    <script src="scripts/app.min.js?v=<?= $app->version ?>"></script>

    <?php $core->include('body-closing') ?>
    <style>
    #lgpd{
      z-index: 0 !important;
    }
  </style>
  </body>
</html>
