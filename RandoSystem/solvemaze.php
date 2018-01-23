<?php
	

	require_once 'classes/Labirinto.php';
	require_once 'classes/Celula.php';
	require_once 'classes/Solver.php';
	
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}
	
	header('Content-type: application/json');
	
	if (isset($_SESSION['labirinto'])) {
		$labirinto = $_SESSION['labirinto'];
		$solver = new Solver();
		$caminho = $solver->solve($labirinto);
		echo json_encode($caminho);
	}

?>