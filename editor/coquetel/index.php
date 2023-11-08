<?php

$page_title = 'Publicados';

include_once 'header.php';

?>

  	<div id="container">
      <p class="topLinks">
        <a class="topLink active">Publicados</a>
        <a class="topLink" href="palavras-cruzadas">Palavras cruzadas</a>
        <a class="topLink" href="sudoku">Sudoku</a>
      </p>

      <div class="listRow header">
        <div class="col">data</div>
        <div class="col">cruzadas</div>
        <div class="col">sudoku</div>
        <div class="col"></div>
      </div>

      <?php

      $root_path = explode('/atelie/', __DIR__)[0] . '/atelie/public';

      for ($i = 15; $i > -30; $i -= 1) {
        echo '<div class="listRow">';

        $timestamp = mktime(0, 0, 0, date('m'), date('d') + $i, date('Y'));

        echo '<div class="col">' . date('d/m/Y', $timestamp) . '</div>';

        $year = date('Y', $timestamp);
        $month = date('m', $timestamp);
        $day = date('d', $timestamp);

        $game = 'palavras-cruzadas';

        $estadao_path = "$root_path/jogos/$game/$year/$month/$day";

        if (file_exists($estadao_path)) {
          echo '<div class="col"><a href="https://arte.estadao.com.br/jogos/palavras-cruzadas/?d=' . "$year-$month-$day" . '" target="_blank">sim</a></div>';
        } else {
          echo '<div class="col">não</div>';
        }

        $game = 'sudoku';

        $estadao_path = "$root_path/jogos/$game/$year/$month/$day";

        if (file_exists($estadao_path)) {
          echo '<div class="col"><a href="https://arte.estadao.com.br/jogos/sudoku/?d=' . "$year-$month-$day" . '" target="_blank">sim</a></div>';
        } else {
          echo '<div class="col">não</div>';
        }

        echo '</div>';
      }

      ?>
    </div>

    <button id="excludeBtn">excluir</button>
	  <script src="js/jquery-2.1.3.min.js"></script>
  	<script src="js/main_list.js?v=1.0.1"></script>
  </body>
</html>
