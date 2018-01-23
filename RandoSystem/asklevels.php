<?php
	
	require_once 'classes/Level.php';
	require_once 'classes/GameLogic.php';
	
	if(!isset($_SESSION) || count($_SESSION) == 0) 
	{ 
		session_start(); 
	}  
	

	$gl = new GameLogic();
	echo "<div id='div-popup'>";
	$saida = "";
	$clicks = "";
	foreach ($_SESSION['levels'] as $level) {
		if ($level->number <= $_SESSION['gamelevel']) {
			$saida = $saida . "<br/><img valign='middle' height='80' id='img-level". $level->number .  
					"' src='images/" . $level->picture . ".png'";
			if ($level->number == $_SESSION['gamelevel']) {
				$texto1 = $gl->translate("selecnivel");
				$texto1 = str_replace("@1",$gl->translate($level->title),$texto1);
				echo $texto1 . "<br/>"; 
				$saida = $saida . " class='selected'";
			}
			else {
				$saida = $saida . " class='imagelevel'";
			}
			$saida = $saida . " onclick='carregar(" . $level->number . ", \"" . $level->picture . "\" " .
								",\"" . $gl->translate($level->title) . "\"" . 
								")' />";
			$saida = $saida . "<span class='titlelevel'>" . $gl->translate($level->title) . "</span>";
			$clicks = $clicks . " $('#img-level'" . $level->number . ").click(function() { " .
										"currentlevel = " . $level->number . ";" .
	  							"});"; 
		}
	}
	echo $saida . "</div>";
?>
