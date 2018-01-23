<?php

require_once 'classes/Navegador.php';

$gl = new GameLogic();
$tp =  Navegador::analisar();
$browser = $tp->nome;
if ($tp->versao == 0) {
	$browser = $gl->translate($tp->nome);
}

echo "Seu navegador e&acute;: " . $browser . ", vers&atilde;o: " . $tp->versao . " ok: " . $tp->ok;
?>