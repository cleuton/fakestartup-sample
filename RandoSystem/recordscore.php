<?php
	require_once 'classes/DB_Manager.php';
	require_once 'classes/GameLogic.php';
	$final = time();
	
	function dbRecord() {
		GLOBAL $final;
		$segundos = $final - $_SESSION['gametime'];
		$gl = new GameLogic();
		$mudou = $gl->recordScore($segundos);
		$tempototal = date("H:i:s", $segundos);
		$retorno = $gl->translate("tempo") . " " . $tempototal . $gl->translate("msgfinal");
		if ($mudou) {
			$retorno = $retorno . " " . $gl->translate("msgmudou");
		}
		$quebrou = $gl->verificarRecords();
		if ($quebrou) {
			$retorno = $retorno . " " . $gl->translate("msgrecord");
		}
		return $retorno;
	}
?>
<div id="div-record">
<span id="spn-record"><?php echo dbRecord(); ?></span>
<br/><br/><div id="spn-fecharrecord" onclick="fechar();">Ok</div>
</div>