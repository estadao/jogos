<?php

$page_title = 'Palavras Cruzadas';

include_once 'header.php';

?>

  	<div id="container">
      <p class="topLinks">
        <a class="topLink" href="./">Publicados</a>
        <a class="topLink active">Palavras cruzadas</a>
        <a class="topLink" href="sudoku">Sudoku</a>
      </p>

      <div id="sendResponse" class="formRow"></div>

	  	<div id="form">

	  		<h1>Nova cruzada</h1>

        <p class="intro formRow">
          Selecione a data e a imagem em JPG da cruzada. A imagem deve estar na proporção correta, sem espaços brancos fora das colunas
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

          <input type="hidden" id="game" name="game" value="palavras-cruzadas" />
	  			<input type="hidden" id="pDir" name="pDir" value="" />
	  			<input type="hidden" id="pText" name="pText" value="" />
          <input type="hidden" id="overwrite" name="overwrite" value="false" />

		  	</div>



		  	<div id="textInput" class="formRow">

          <p class="intro formRow">Cole ou digite o texto das respostas</p>

  				<label class="textLabel" for="textBox">
  					<!--<span>Digite o texto:</span><br>-->
  					<textarea type="text" name="textBox" id="textBox" placeholder="Texto da cruzada"></textarea>
  				</label>

          <p class="intro formRow">Confira se o número de colunas e linhas precisa ser ajustado e clique nas células que precisam ser desabilitadas</p>

  	  		<div class="formRow">
  	  			<label class="numLabel" for="columnNum"><span>Colunas:</span><input class="inputNum" type="number" step="1" name="columnNum" id="columnNum" class="inputfile" value="10" /></label>
  	  			<label class="numLabel" for="lineNum"><span>Linhas:</span><input class="inputNum" type="number" step="1" name="lineNum" id="lineNum" class="inputfile" value="15" /></label>
  			  </div>
			  </div>

		  	<div id="puzzlePreview" class="formRow">

		  		<img id="puzzleImg" src="#" alt="Cruzada" />
		  		<div id="cellHolder"></div>

		  		<p style="small">
		  			<b>1º clique:</b> desabilita a célula<br>
		  			<b>2º clique:</b> divide a célula com traço para a direita<br>
		  			<b>3º clique:</b> divide a célula com traço para a esquerda
          </p>

		  		<button id="uploadBt">Enviar</button>

		  	</div>
	  	</div>

	  </div>

    <button id="excludeBtn">excluir</button>

	  <script src="js/jquery-2.1.3.min.js"></script>
  	<script src="js/main.js?v=1.0.4"></script>
  </body>
</html>
