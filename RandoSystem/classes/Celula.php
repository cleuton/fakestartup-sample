<?php
	require 'includes/constantes.php';
	
class Celula {
	public $paredes = array(true, true, true, true);
	public $visitada = false;
	public $inicio = false;
	public $fim = false;
	public $x = 0;
	public $y = 0;
	public $ocupada = false;
	public $objeto = 0;
}


?>