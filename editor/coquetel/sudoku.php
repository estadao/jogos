<?php

$page_title = 'Sudoku';

include_once 'header.php';

?>

  	<div id="container">
      <p class="topLinks">
        <a class="topLink" href="./">Publicados</a>
        <a class="topLink" href="palavras-cruzadas">Palavras cruzadas</a>
        <a class="topLink active">Sudoku</a>
      </p>

      <div id="sendResponse" class="formRow"></div>

	  	<div id="form">

	  		<h1>Novo sudoku</h1>

        <p class="intro formRow">
          Selecione a data e a imagem em JPG do sudoku. A imagem deve estar na proporção correta, sem espaços brancos fora das colunas
        </p>

		  	<div class="formRow">
		  		<div id="dateGroup">
		  			<button id="dateBt">3/6/2016</button>

		  			<div class="datepicker">

	                  <div class="monthName">
	                    <span class="currYear">2016</span>
	                    <a href="#" id="prevMonth" class="fa fa-angle-left"></a>
	                    <span class="currMonth">JUNHO</span>
	                    <a href="#" id="nextMonth" class="fa fa-angle-right"></a>
	                  </div>

	                  <ul class="weekDays">
	                    <li>D</li>
	                    <li>S</li>
	                    <li>T</li>
	                    <li>Q</li>
	                    <li>Q</li>
	                    <li>S</li>
	                    <li>S</li>
	                  </ul>

	                  <ul class="monthDays"></ul>

	                </div>
		  		</div>

		  		<input type="file" name="pFile" id="pFile" class="inputfile" data-multiple-caption="{count} files selected" accept=".jpg" />

		  		<label for="pFile" id="pFileLabel">
		  			<figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg></figure>
		  			<span>Escolha a imagem</span>
		  		</label>

          <input type="hidden" id="game" name="game" value="sudoku" />
	  			<input type="hidden" id="pDir" name="pDir" value="" />
	  			<input type="hidden" id="pText" name="pText" value="" />
          <input type="hidden" id="overwrite" name="overwrite" value="false" />

		  	</div>

		  	<div id="textInput" class="formRow">
  				<label class="textLabel" for="textBox">
  					<!-- <span>Digite o texto:</span><br> -->
  					<textarea type="text" name="textBox" id="textBox" placeholder="Respostas do Sudoku"></textarea>
  				</label>
			  </div>

		  	<div id="puzzlePreview" class="formRow">

          <p class="intro formRow">Clique nas células que precisam ser desabilitadas</p>

  	  		<div class="formRow">
  	  			<label class="numLabel" for="columnNum"><span>Colunas:</span><input class="inputNum" type="number" step="1" name="columnNum" id="columnNum" class="inputfile" value="9" /></label>
  	  			<label class="numLabel" for="lineNum"><span>Linhas:</span><input class="inputNum" type="number" step="1" name="lineNum" id="lineNum" class="inputfile" value="9" /></label>
  			  </div>

		  		<img id="puzzleImg" src="#" alt="Cruzada" />
		  		<div id="cellHolder"></div>
		  		<p style="small">
            <b>1º clique OU botão direito:</b> desabilita a célula<br>
            <b>2º clique:</b> reabilita a célula<br>

            <button id="invertBtn" class="fa fa-refresh" ></button>
          </p>

		  		<button id="uploadBt">Enviar</button>

		  	</div>

	  	</div>
	</div>

    <button id="excludeBtn">excluir</button>
	  <script src="js/jquery-2.1.3.min.js"></script>
  	<script src="js/main_sudoku.js?v=1.0.4"></script>
  </body>
</html>
