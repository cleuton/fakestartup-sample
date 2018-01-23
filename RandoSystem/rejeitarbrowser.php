<?php
	$browser = $_SERVER['HTTP_USER_AGENT'];
?>
<html>
<head>
</head>
<body>
<h3>Suporte ao navegador</h3>
<p>Ol&aacute;,</p>
<p>Lamentamos, mas n&atilde;o reconhecemos o seu navegador. Adorar&iacute;amos ter nosso jogo funcionando em qualquer navegador de Internet, por&eacute;m,  
devido a restri&ccedil;&otilde;es de tempo e or&ccedil;amento, n&atilde;o &eacute; poss&iacute;vel. Somos desenvolvedores independentes ("Indie"), logo, n&atilde;o temos recursos para verificar e 
adaptar nosso c&oacute;digo para cada navegador existente. </p>
<p>A assinatura do seu navegador &eacute;: <?php echo $browser; ?></p>
<p>Nosso jogo foi constru&iacute;do com as mais modernas tecnologias da Web: HTML 5, CSS 3 e Ajax, e infelizmente elas n&atilde;o s&atilde;o suportadas 
da mesma maneira por alguns navegadores. Ent&atilde;o, n&otilde;s testamos o jogo nos seguintes navegadores (por favor, note a vers&atilde;o):
<ul>
<li>Microsoft Internet Explorer vers&atilde;o 9 (ou mais recente)</li>
<li>Mozilla Firefox vers&atilde;o 9 (ou mais recente)</li>
<li>Google Chrome vers&atilde;o 14 (ou mais recente)</li>
<li>Apple Safari vers&atilde;o 5 (ou mais recente)</li>
</ul>
</p><p>Nota: Se utilizar Microsoft Internet Explorer 9, certifique-se de que a "Compatibilidade de navegador" esteja como "Internet Explorer 9"</p>

<p>Embora seja poss&iacute;vel que o jogo funcione em seu navegador, n&atilde;o podemos garantir isso. Se quiser testar,
por favor clique no link abaixo ("Eu quero testar") e nos diga se funcionou.</p>
<p>A imagem principal do jogo deve aparecer assim (clique no menu "Novo"):</p>
<img src="images/imagemjogo.png" border="2"></img>
<p>Se quiser testar, por favor clique no link abaixo e verifique se o resultado est&aacute; como o da imagem.</p>
<p><a href="index.php?try=1">Eu quero testar</a></p>
</body>
</html>
