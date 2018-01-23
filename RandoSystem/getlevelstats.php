<?php

	require_once 'classes/GameLogic.php';
	require_once 'classes/Level.php';
	require_once 'classes/DB_Manager.php';
	require_once 'classes/GameLogic.php';
	
	
	echo verstats();

	function verstats() {
		$resultado = "";
		$passou = false;
		$dbm = new DB_Manager();
		$gl = new GameLogic();
		$userId = $_SESSION['userid'];
		$gameLevel = $_SESSION['gamelevel'];
		
		$level = null;
		
		foreach ($_SESSION['levels'] as $l) {
			if ($l->number == $gameLevel) {
				$level = $l;
				break;
			}
		}
		
		// Existe o nível final, que não tem média..
		$mediaUsuario = 0;
		if ($level->average > 0) {
			$comando = "select count(id) as qtde, avg(seconds) as media from user_games where id = " .
					$userId ." and level_played = " . $gameLevel ." and seconds <= " . 
					$level->average . ";";
			$resultadoQuery = $dbm->query($comando);
			if ($resultadoQuery) {
				if (($linha = $dbm->fetch_array($resultadoQuery))) {
					$falta = $level->games - $linha[0];
					$resultado = $gl->translate("infonivel");
					$resultado = str_replace("@1", $falta, $resultado);
					if ($linha[0] > 0) {
						$mediaUsuario = $linha[1];
					}
				}
				else {
					
					$resultado = $gl->translate("infonivel");
					$resultado = str_replace("@1", $level->average, $resultado);
				}
			}
			else {
				$resultado = $gl->translate("infonivel");
				$resultado = str_replace("@1", "0", $resultado);
			}			
		}
		else {
			$resultado = $gl->translate("ultimonivel");
		}
		$nivel = $gl->translate("nivelatual");
		$nivel = str_replace("@1", $gl->translate("$level->title"), $nivel);
		$textoMedia = "<br/>" . $gl->translate("media");
		$textoMedia = str_replace("@1", $mediaUsuario, $textoMedia);
		$textoMedia = $textoMedia . "<br/>" . $gl->translate("mediamudar"); 
		$textoMedia = str_replace("@1", $level->average, $textoMedia);
		return $nivel . ". " . $resultado . $textoMedia;
	}
?>