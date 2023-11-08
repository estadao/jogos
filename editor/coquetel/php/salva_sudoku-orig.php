<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// emails que podem acessar o gerador
$allowed_emails = array(
  0 => "fabio.tdz@gmail.com",
  1 => "carolinex@gmail.com",
  2 => "arte.estado@gmail.com",
  3 => "nac.redacao@gmail.com",
  4 => "nacredacao@gmail.com",
  5 => "estadao.nac@gmail.com",
  6 => "estadaonac@gmail.com",
  7 => "fontespereira88@gmail.com"
);

// se a sessão está aberta
// e o usuário liberado
// se a sessão está aberta
// e o usuário liberado
function saveFiles(){
  global $allowed_emails;

  if (  isset($_SESSION["user"]) &&
      in_array($_SESSION["user"], $allowed_emails)) {

			if(!empty($_POST['pText']) && !empty($_POST['pDir']) ){

					$text = $_POST['pText'];
					$fname = "sudoku.txt";
					$img = $_FILES['pFile'];
					$imgname = "sudoku.jpg";
					$folder = "../../".$_POST['pDir'];
					$file_attached = false;
          $overwrite = $_POST['overwrite'];

          $dirs = glob('../../*', GLOB_ONLYDIR);
          rsort($dirs);

          umask(0);

          // cria o diretório da data selecionada
          // se já existir, retorna aviso
					if( !file_exists($folder) ){
						mkdir($folder, 0777, true) or die("Não foi possível criar o diretório");
					}else if($overwrite == false){
            $overwrite == false;
            echo json_encode("Existing folder");
            exit;
          }else{
            chmod ( $folder, 0777 ) or die("Não foi possível editar o diretório");
          }

          // cria o diretório '/yyy_mm_dd/cruzada'
          createOrUpdateDir($folder."/sudoku");
          // cria o diretório '/yyy_mm_dd/parceiro'
          createOrUpdateDir($folder."/parceiro");
          // cria o diretório '/yyy_mm_dd/parceiro/cruzada'
          createOrUpdateDir($folder."/parceiro/sudoku");

          // copia os dois modelos de sudoku
          // para o diretorio da data ( yyy_mm_dd )
					recursive_copy("modelo_estadao/sudoku", $folder."/sudoku");
          recursive_copy("modelo_parceiro/sudoku", $folder."/parceiro/sudoku");

          // sobrescreve o arquivo de dados
          // com o novo texto
					if( file_exists($folder."/sudoku/dados") ){
            /* create a stream context telling PHP to overwrite the file */
            $options = array('ftp' => array('overwrite' => true));
            $stream = stream_context_create($options);

            if( !file_put_contents($folder."/sudoku/dados/".$fname, $text, 0, $stream) ){
              echo json_encode("Não foi possível salvar o arquivo de texto");
              exit;
            }
  				}else{
  					echo json_encode("Diretório 'dados' ainda não foi criado");
            exit;
  				}

          // sobrescreve o arquivo de dados
          // no diretório do parceiro com o novo texto
          if( file_exists($folder."/parceiro/sudoku/dados") ){
            /* create a stream context telling PHP to overwrite the file */
            $options = array('ftp' => array('overwrite' => true));
            $stream = stream_context_create($options);

            file_put_contents($folder."/parceiro/sudoku/dados/".$fname, $text, 0, $stream) or die("Não foi possível salvar o arquivo de texto (parceiro)");
  				}else{
  					echo json_encode("Diretório 'dados' não foi criado (parceiro)");
            exit;
  				}

					// se foi enviado o arquivo de imagem...
          if(isset($_FILES['pFile'])) {
		        //get file details we need
		        $file_tmp_name    = $img['tmp_name'];
		        $file_name        = $img['name'];
		        $file_size        = $img['size'];
		        $file_type        = $img['type'];
		        $file_error       = $img['error'];

            // define o caminho onde será salva a imagem
		        $uploadfile = $folder."/sudoku/images/" . basename($imgname);
            $partnerfile = $folder."/parceiro/sudoku/images/" . basename($imgname);


            // salva na pasta do modelo do estadão
            if( file_exists( $folder."/sudoku/images" ) ){

  						if( move_uploaded_file($img['tmp_name'], $uploadfile) ){
                // copia a imagem para o modelo dos parceiros
                // Em seguida cria o zip do parceiro
                if( file_exists( $folder."/parceiro/sudoku/images" ) ){
                  recursive_copy( $uploadfile, $partnerfile );

                  if( createZip( $_POST['pDir'], $folder."/parceiro/sudoku/" ) ){
                    echo json_encode("../".$_POST['pDir']."/sudoku/ ");
                    exit;
                  }else{
                    echo json_encode("Não foi possivel criar o zip");
                    exit;
                  }
        				}else{
        					echo json_encode( "Diretório 'images' não foi criado (parceiro)" );
                  exit;
        				}
              }else{
                echo json_encode("Não foi possível enviar a imagem");
                exit;
              }
  					}else{
  						echo json_encode("Diretório 'images' não foi criado");
              exit;
  					}

				  }
				}else{
					echo json_encode("Não foram recebidos dados para salvamento");
				}
  }else if ( isset( $_SESSION["user"] ) ){
  	echo json_encode($_SESSION["user"]);
  }else{
  	echo json_encode("not allowed");
  }
}
saveFiles();

// Copia o diretório completo
// inclusive pastas internas
function recursive_copy($source, $dest){

		if(is_dir($source)) {
				$dir_handle=opendir($source);
				while($file=readdir($dir_handle)){
						if($file!="." && $file!=".."){
								if(is_dir($source."/".$file)){
										if(!is_dir($dest."/".$file)){
												mkdir($dest."/".$file);
										}
										recursive_copy($source."/".$file, $dest."/".$file);
								} else {
										copy($source."/".$file, $dest."/".$file);
								}
						}
				}
				closedir($dir_handle);
		} else {
				copy($source, $dest);
		}
}

function createOrUpdateDir($dir){
  if( !file_exists( $dir ) ){
    mkdir( $dir, 0777, true ) or die( "Não foi possível criar o diretório /".dir );
    chmod( $dir, 0777 ) or die( "Não foi possível editar as permissões do diretório /".dir );
  }else{
    chmod( $dir, 0777 ) or die( "Não foi possível editar as permissões do diretório /".dir );
  }
}

// cria arquivo zip com todos os arquivos
function createZip($date, $dateFolder){
	$success = false;
	// Get real path for our folder
	$rootPath = realpath($dateFolder);

	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open( '../../sudoku_'.$date.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE );

	// Create recursive directory iterator
	/** @var SplFileInfo[] $files */
	$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ($files as $name => $file) {
			// Skip directories (they would be added automatically)
			if (!$file->isDir()){
					// Get real and relative path for current file
					$filePath = $file->getRealPath();
					$relativePath = substr($filePath, strlen($rootPath) + 1);

					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
			}
	}

	// Zip archive will be created only after closing object
	$zip->close();
	$success = true;

	return $success;
}

?>
