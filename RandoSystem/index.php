<?php 
	require_once 'classes/DB_Manager.php';
	require_once 'classes/GameLogic.php';
	require_once 'classes/Navegador.php';
	
	session_destroy();
	session_start(); 

	$gl = new GameLogic();
	
	if (!isset($_SESSION['userid'])) {
		// Isto terá que ser substituído pelo checkin do facebook
		$_SESSION['gl'] = $gl;
		$gl->logon(9055);
		$gl->loadapc();
		if (!isset($_GET['try'])) {
			$tipobrowser = Navegador::analisar();
			if (!$tipobrowser->ok) {
				if ($_SESSION['userlocale'] == "pt") {
					header( 'Location: rejeitarbrowser.php' ) ;
				}
				else {
					header( 'Location: rejectbrowser.php' ) ;
				}
				exit;
			}
		}
	}
	
?>
<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=9" >
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>RandoSystem</title>
<link href='http://fonts.googleapis.com/css?family=Irish+Grover|Frijole|Knewave' rel='stylesheet' type='text/css' />
<link href="styles/estilo.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js">
</script>
<script type="text/javascript" src="resources/modal.popup.js"></script>
<script type="text/javascript">
labirinto = null;
resolvido = true;
posx = 0;
posy = 0;
KEY_UP1 = 73; // i
KEY_DOWN1 = 75; // k
KEY_RIGHT1 = 76; // l
KEY_LEFT1 = 74; // j
KEY_UP2 = 69; // e
KEY_DOWN2 = 83; // s
KEY_RIGHT2 = 68; // d
KEY_LEFT2 = 65; // a
currentLevel = 1;

bkcolor = "#556B2F";
wallcolor = "#CD5C5C";
shadowcolor = "#000000";
markcolor = "#7FFF00";
solvercolor = "#FFD700";
caminho = null;
cellsize = 20; 
linewidth = Math.floor(cellsize / 5);
shadowwidth = Math.floor(linewidth * 0.7);
fadeout = 300;

shadowlineoffset = 2;
startOffset = linewidth + shadowlineoffset;
padsize = startOffset + shadowlineoffset;



NORTE = 0; // cima
SUL = 1;   // baixo
LESTE = 2; // direita
OESTE = 3; // esquerda
segundos = 0;
timer = null;

$(document).ready(function(){
	  
	  $("#spn-desisto").click(function(){
	        $.getJSON('solvemaze.php', function(data) {
		        if (!resolvido) {
			        clearInterval(timer);
		            caminho = data;
		            resolver();
		        }
	        });
	  });
	  $("#img-setacima").click(function(){
			mover(0,-1);
	  });
	  $("#img-setabaixo").click(function(){
			mover(0,+1);
	  });
	  $("#img-setadir").click(function(){
			mover(+1,0);
	  });
	  $("#img-setaesq").click(function(){
			mover(-1,0);
	  });
	  // 37-left, 38-up, 39-right, 40 - down
	  $(document).bind('keyup', function(e){
		    var code = e.keyCode;
		    switch(code) {
		    case KEY_LEFT1: 
			    // Left
			    mover(-1,0);
			    break;
		    case KEY_LEFT2: 
			    // Left
			    mover(-1,0);
			    break;
		    case KEY_UP1: 
			    // Up
			    mover(0,-1);
			    break;
		    case KEY_UP2: 
			    // Up
			    mover(0,-1);
			    break;
		    case KEY_RIGHT1: 
			    // Right
			    mover(+1,0);
			    break;
		    case KEY_RIGHT2: 
			    // Right
			    mover(+1,0);
			    break;
			case KEY_DOWN1: 
			    // Down
			    mover(0,+1);
			    break;
			case KEY_DOWN2: 
			    // Down
			    mover(0,+1);
			    break;
			    
			}
		});
});

function carregar(nivel, imagem, titulo) {
	clearInterval(timer);
	$("#spn-stats").load("getlevelstats.php");
  	$("#img-levelimg").attr("src", "images/" + imagem + ".png"); 
  	$("#spn-levelname").html(titulo);
    $.getJSON('newmaze.php?level=' + nivel, function(data) {
        labirinto = data;
        if (labirinto.valido == "true") {
    		segundos = 0;
    		resolvido = false;
            timer = setInterval(function() {
                segundos++;		           
                $('timer').text(secondsToTime(segundos));
            	}, 1000);
            redesenhar();
        }
        else {
            $("#msg").html("ERROR MAZE 1");
        }

    });
    closePopup(fadeout);
    
}

function redesenhar() {
	
	resolvido = false;
	$("text").html("");
	posx = 0;
	posy = 0;
	var tela=document.getElementById("tela");
	var ctx=tela.getContext("2d");
	caminho = null;
	var x = cellsize;
	var y = cellsize;
	$("#canvas")
    .attr('width', $(window).width())
    .attr('height', $(window).height());
	$("#tela")
    	.attr('width',cellsize * labirinto.colunas + 30)
    	.attr('height',cellsize * labirinto.linhas + 30);
	
	clearCanvas(ctx,tela);

	// Background
	ctx.fillStyle=bkcolor;
	ctx.fillRect(x-shadowlineoffset,y-shadowlineoffset,labirinto.linhas * cellsize+ shadowlineoffset * 4, labirinto.colunas * cellsize + shadowlineoffset * 4);
	
	ctx.fillStyle=markcolor;
	labirinto.celulas[posy][posx].ocupada = "true";
	ctx.fillRect(x+startOffset,y+startOffset,cellsize-padsize,cellsize-padsize);
	
	
	for (var linha = 0; linha < labirinto.linhas; linha++) {
		x = cellsize;
		for (var coluna = 0; coluna < labirinto.colunas; coluna++) {
			var celula = labirinto.celulas[linha][coluna];

			if (celula.paredes[NORTE] == "true") {
				ctx.strokeStyle=shadowcolor;
				ctx.lineWidth = linewidth + shadowwidth;
				ctx.beginPath();
				ctx.moveTo(x+shadowlineoffset,y+shadowlineoffset);
				ctx.lineTo(x+cellsize+shadowlineoffset,y+shadowlineoffset);
				ctx.stroke();
			
				ctx.strokeStyle=wallcolor;
				ctx.lineWidth = linewidth;
				ctx.beginPath();
				ctx.moveTo(x,y);
				ctx.lineTo(x+cellsize,y);
				ctx.stroke();
			}
			
			if (celula.paredes[LESTE] == "true") {
				ctx.strokeStyle=shadowcolor;
				ctx.lineWidth = linewidth + shadowwidth;
				ctx.beginPath();
				ctx.moveTo(x+cellsize+shadowlineoffset,y+shadowlineoffset);
				ctx.lineTo(x+cellsize+shadowlineoffset,y+cellsize+shadowlineoffset);
				ctx.stroke();

				ctx.strokeStyle=wallcolor;
				ctx.lineWidth = linewidth;
				ctx.beginPath();
				ctx.moveTo(x+cellsize,y);
				ctx.lineTo(x+cellsize,y+cellsize);
				ctx.stroke();
			}
			
			if (celula.paredes[SUL] == "true") {
				ctx.strokeStyle=shadowcolor;
				ctx.lineWidth = linewidth + shadowwidth;
				ctx.beginPath();
				ctx.moveTo(x+shadowlineoffset,y+cellsize+shadowlineoffset);
				ctx.lineTo(x+cellsize+shadowlineoffset,y+cellsize+shadowlineoffset);
				ctx.stroke();

				ctx.strokeStyle=wallcolor;
				ctx.lineWidth = linewidth;
				ctx.beginPath();
				ctx.moveTo(x,y+cellsize);
				ctx.lineTo(x+cellsize,y+cellsize);
				ctx.stroke();
			}
			
			if (celula.paredes[OESTE] == "true") {
				ctx.strokeStyle=shadowcolor;
				ctx.lineWidth = linewidth + shadowwidth;
				ctx.beginPath();
				ctx.moveTo(x+shadowlineoffset,y+shadowlineoffset);
				ctx.lineTo(x+shadowlineoffset,y+cellsize+shadowlineoffset);
				ctx.stroke();

				ctx.strokeStyle=wallcolor;
				ctx.lineWidth = linewidth;
				ctx.beginPath();
				ctx.moveTo(x,y);
				ctx.lineTo(x,y+cellsize);
				ctx.stroke();
			}
			
			x += cellsize;
		}
		y += cellsize;
	}
	
}

function resolver() {
	if (caminho != null) {
		
		var tela=document.getElementById("tela");
		var ctx=tela.getContext("2d");
		resolvido = true;
		ctx.fillStyle=solvercolor;
		for (var i = 0; i < caminho.length; i++) {
			var celula = caminho[i];
			var y = celula.y * cellsize + cellsize;
			var x = celula.x * cellsize + cellsize;
			ctx.fillRect(x+startOffset,y+startOffset,cellsize-padsize,cellsize-padsize);
		}
		var source = "desistir.php";
	    var width = "200";
	    var align = "center";
	    var top = 100;
	    var padding = 10;
	    var backgroundColor = "#DC143C";
	    var borderColor = "#FF8C00";
	    var borderWeight = 4;
	    var borderRadius = 15;
	    var fadeOutTime = fadeout;
	    var disableColor = "#666666";
	    var disableOpacity = 40;
	    var loadingImage = "images/loading.gif";
	    
		modalPopup( align,
			    top,
			    width,
			    padding,
			    disableColor,
			    disableOpacity,
			    backgroundColor,
			    borderColor,
			    borderWeight,
			    borderRadius,
			    fadeOutTime,
			    source,
			    loadingImage );
		

	}
}

function fechar() {
	closePopup(fadeout);
}

function clearCanvas(context, canvas) {
	  context.clearRect(0, 0, canvas.width, canvas.height);
	  var w = canvas.width;
	  canvas.width = 1;
	  canvas.width = w;
}

function mover (dx, dy) {
	if (!resolvido) {
		var tela=document.getElementById("tela");
		var ctx=tela.getContext("2d");
		var celula = labirinto.celulas[posy][posx];
		if (dx != 0) {
			if (dx > 0) {
				if (celula.paredes[LESTE] == "true") {
					return;
				}
			}
			else {
				if (celula.paredes[OESTE] == "true") {
					return;
				}		
			}
		}
		else if (dy != 0) {
			if (dy > 0) {
				if (celula.paredes[SUL] == "true") {
					return;
				}
			}
			else {
				if (celula.paredes[NORTE] == "true") {
					return;
				}
				if (posy == 0 && posx == 0) {
					return;
				}
			}
		}
		var y = posy * cellsize + cellsize;
		var x = posx * cellsize + cellsize;
		var oldy = y;
		var oldx = x;
		var oldposx = posx;
		var oldposy = posy;
		posx += dx;
		posy += dy;
		y = posy * cellsize + cellsize;
		x = posx * cellsize + cellsize;
		if (labirinto.celulas[posy][posx].ocupada != "false") {
			ctx.fillStyle=bkcolor;
			ctx.fillRect(oldx+startOffset,oldy+startOffset,cellsize-padsize,cellsize-padsize);
			labirinto.celulas[oldposy][oldposx].ocupada = "false";
		}
		ctx.fillStyle=markcolor;
		labirinto.celulas[posy][posx].ocupada = "true";
		ctx.fillRect(x+startOffset,y+startOffset,cellsize-padsize,cellsize-padsize);
		if (posy == (labirinto.linhas - 1) && posx == (labirinto.colunas - 1)) {
			resolvido = true;
			clearInterval(timer);
			var source = "recordscore.php";
		    var width = "200";
		    var align = "center";
		    var top = 100;
		    var padding = 10;
		    var backgroundColor = "#DC143C";
		    var borderColor = "#FF8C00";
		    var borderWeight = 4;
		    var borderRadius = 15;
		    var fadeOutTime = fadeout;
		    var disableColor = "#666666";
		    var disableOpacity = 40;
		    var loadingImage = "images/loading.gif";
		    
			modalPopup( align,
				    top,
				    width,
				    padding,
				    disableColor,
				    disableOpacity,
				    backgroundColor,
				    borderColor,
				    borderWeight,
				    borderRadius,
				    fadeOutTime,
				    source,
				    loadingImage );
		}

		//spn-stats
		$("#spn-stats").load("getlevelstats.php");
	}
}

function secondsToTime(secs) {
    var hours = Math.floor(secs / (60 * 60));
   
    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);
 
    var divisor_for_seconds = divisor_for_minutes % 60;
    var seconds = Math.ceil(divisor_for_seconds);
   
    var obj = ((hours < 10) ? "0" + hours : "" + hours) + ":" + 
    		  ((minutes < 10) ? "0" + minutes : "" + minutes) + ":" + 
    		  ((seconds < 10) ? "0" + seconds : "" + seconds);
    return obj;
}

</script>
<script type="text/javascript">
 // modal popup
  $(document).ready(function() {
 
    //Change these values to style your modal popup
   
    var width = "500";
    var align = "center";
    var top = 100;
    var padding = 10;
    var backgroundColor = "#DC143C";
    var borderColor = "#FF8C00";
    var borderWeight = 4;
    var borderRadius = 15;
    var fadeOutTime = fadeout;
    var disableColor = "#666666";
    var disableOpacity = 40;
    var loadingImage = "images/loading.gif";
 
    //This method initialises the modal popup
     var sourceNovo = "asklevels.php";
     var sourceRecordes = "recordes.php";
     var sourceGaleria = "galeria.php";
    $("#spn-novo").click(function() {
 
        modalPopup( align,
		    top,
		    width,
		    padding,
		    disableColor,
		    disableOpacity,
		    backgroundColor,
		    borderColor,
		    borderWeight,
		    borderRadius,
		    fadeOutTime,
		    sourceNovo,
		    loadingImage );
 
    });	

    $("#spn-recordes").click(function() {
    	 
        modalPopup( align,
		    top,
		    width,
		    padding,
		    disableColor,
		    disableOpacity,
		    backgroundColor,
		    borderColor,
		    borderWeight,
		    borderRadius,
		    fadeOutTime,
		    sourceRecordes,
		    loadingImage );
 
    });	

    $("#spn-galeria").click(function() {
   	 
        modalPopup( align,
		    top,
		    width,
		    padding,
		    disableColor,
		    disableOpacity,
		    backgroundColor,
		    borderColor,
		    borderWeight,
		    borderRadius,
		    fadeOutTime,
		    sourceGaleria,
		    loadingImage );
 
    });	
 
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            closePopup(fadeOutTime);
        }
    });

 
  });
 
</script>

</head>
<body>
<div id="div-topo">
	<img id="img-levelimg" border="0" src="images/inicial.png"/> 
	<span id="spn-levelname"></span>
	<span id="spn-titulo1">Rando</span>
	<span id="spn-titulo2">System</span>
	<span id="spn-subtitulo"><?php echo $gl->translate("titulo"); ?></span>
	<div id="div-menu">
		<span id="spn-novo"><?php echo $gl->translate("novo"); ?></span>
		<span id="spn-desisto"><?php echo $gl->translate("desisto"); ?></span>
		<span id="spn-ajuda"><?php echo $gl->translate("ajuda"); ?></span>
		<span id="spn-sobre"><?php echo $gl->translate("sobre"); ?></span>
	</div>
	<div id="div-setas">
		<img id="img-setaesq"   src="images/setaesq.png" />
		<img id="img-setacima"  src="images/setacima.png" />
		<img id="img-setabaixo" src="images/setabaixo.png" />
		<img id="img-setadir"   src="images/setadir.png" />
	</div>
	<div id="div-timer"><timer></timer></div>
</div>
<div id="div-conteudo">
	<br/><span id="msg"></span>
	<div>
	<canvas id="tela" height="1" width="1"  ></canvas>
	<br/>
	</div>
	<br/><span id="spn-stats"></span>
	<br/><span id="spn-recordes" class="recordes">
	<?php echo $gl->translate("seusrecords"); ?>
	</span>
	<br/><span id="spn-galeria" class="recordes">
	<?php echo $gl->translate("hall"); ?>
	</span>
	</div>


</body>
</html>