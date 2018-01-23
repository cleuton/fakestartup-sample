<?php
	
	require_once 'classes/Labirinto.php';
	require_once 'classes/Celula.php';
	require_once 'classes/GameLogic.php';
	
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	
	header('Content-type: application/json');
	
	$gl = new GameLogic();
	$labirinto = $gl->getMaze($_GET['level']);
	$_SESSION['labirinto'] = $labirinto;
	$_SESSION['gametime'] = time();
	$_SESSION['playedlevel'] = $_GET['level'];
	$sep = "";
	$linsep = "";
	
	$valido = "true";
		
	if (!$labirinto->valido) {
		$valido = "false";
	}
	
	echo "{\"linhas\" : \"" . $labirinto->linhas .
		 "\",\"colunas\" : \"" . $labirinto->colunas . "\"," .
		 "\"valido\" : \"" . $valido . "\", " . 
		 "\"celulas\" : [";
	
	foreach ($labirinto->celulas as $linha) {
		echo $linsep . "\r\n[";
		$linsep = ",";
		$sep = "";
		foreach ($linha as $celula) {
			$cellocupada = "false";
			
			if ($celula->ocupada) {
				$cellocupada = "true";	
			}
			
			echo $sep . "\r\n{\"linha\" : \"" . $celula->y . "\", " .
					"\"ocupada\" : \"" . $cellocupada . "\" , " .
					"\"objeto\"  : \"" . $celula->objeto ."\" , " .
					"\"coluna\" : \"" . $celula->x . "\", \"paredes\" : [";
			//$lincol = "\"L " . $celula->y . " - C " . $celula->x . "\",";
			if ($celula->paredes[0]) {
				echo "\"true\",";
				//echo $lincol;
			}
			else {
				echo "\"false\",";
				//echo $lincol;
				
			}
			if ($celula->paredes[1]) {
				echo "\"true\",";
			}
			else {
				echo "\"false\",";
			}
			if ($celula->paredes[2]) {
				echo "\"true\",";
			}
			else {
				echo "\"false\",";
			}
			if ($celula->paredes[3]) {
				echo "\"true\"]";
			}
			else {
				echo "\"false\"]";
			}
			echo "}";
			
			$sep = ",";
		}
		echo "]";
	}
	echo "\r\n]}";
?>