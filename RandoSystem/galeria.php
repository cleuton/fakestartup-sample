<?php

	require_once 'classes/GameLogic.php';
	require_once 'classes/DB_Manager.php';
	require_once 'classes/Level.php';
?>
<div id="div-record">
<?php 	
	
	echo vergaleria();

	function vergaleria() {
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
		$resultado = "<table>";
		
		// **************** ATENÇÃO: TROCAR "id" POR FOTO OU APELIDO DO USUÁRIO NO FB !!!!!!!!!!!!!!!!!!!!!
		
		$comando = "select * from absolute_records where id = " .
				$userId ." order by level, seconds desc;";
		$resultados = false;
		$resultadoQuery = $dbm->query($comando);
		while (($linha = $dbm->fetch_array($resultadoQuery))) {
			$resultados = true;
			$resultado = $resultado . "<tr><td>" . $gl->translate($_SESSION['levels'][$linha[0]-1]->title) . 
			"</td><td>" . $linha[1] . "</td><td>" .
			$linha[2] . "</td></tr>";			
		}

		if (!$resultados) {
			$resultado = $gl->translate("seminformacoes");
		}
		else {
			$resultado = $resultado . "</table>";
		}
		return $resultado;
	}
?>
</div>
<br/><br/><div id="spn-fecharrecord" onclick="fechar();">Ok</div>