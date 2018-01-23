<?php

require_once 'classes/GameLogic.php';

if(!isset($_SESSION) || count($_SESSION) == 0)
{
	session_start();
}
$gl = $_SESSION['gl'];
?>
<div id="div-desistir">
<span id="spn-desistiu"><?php echo $gl->translate("desistirmsg"); ?></span>
<br/><br/><div id="spn-fechar" onclick="fechar();">Ok</div>
</div>