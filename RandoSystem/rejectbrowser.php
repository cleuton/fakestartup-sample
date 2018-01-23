<?php
	$browser = $_SERVER['HTTP_USER_AGENT'];
?>
<html>
<head>
</head>
<body>
<h3>Browser support</h3>
<p>Hi,</p>
<p>We are sorry, but we could not recognize your browser. We would love to have our game working on any Internet Browser, but, 
due to time and budget restrictions, it is not possible. We are "Indie" developers, and do not have the resources to check and 
adapt our code for every major browser. </p>
<p>Your browser's signature is: <?php echo $browser; ?></p>
<p>Our game is made with the latest web technologies, like: HTML 5, CSS 3 and Ajax, and unfortunately they are not supported 
the same way by some browsers. So, we have tested our game in the following browsers (please note the version):
<ul>
<li>Microsoft Internet Explorer version 9 (or newer)</li>
<li>Mozilla Firefox version 9 (or newer)</li>
<li>Google Chrome version 14 (or newer)</li>
<li>Apple Safari version 5 (or newer)</li>
</ul>
</p><p>Note: If you use Microsoft Internet Explorer 9, be sure to set "Browser compatibility" option to "Internet Explorer 9".</p>

<p>
Although it is possible that our game works in your browser, we can not guarantee this. If you want to try, 
please click the link below ("I want to try") and let us know if it works.</p>
<p>The main image of our game should be seen like that (click on the "New" link):</p>
<img src="images/gameimage.png" border="2"></img>
<p>If you want to try, please click the link below and verify if the result is like the picture</p>
<p><a href="index.php?try=1">I want to try</a></p>
</body>
</html>