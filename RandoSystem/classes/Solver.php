<?php
	require_once 'classes/Labirinto.php';
	require_once 'classes/Celula.php';
	
	class Solver {
	
		private $pilha = array();
		private $corrente = null; // Celula
		private $caminho = array(); // Celulas
		
		
		// incrementos das coordenadas para: 
		//  NORTE, SUL, LESTE, OESTE
		private $incrementos = array(
					array(0,-1), 
					array(0, 1),
					array(1, 0),
					array(-1, 0)
				);
		private $visitadas = array(); // duas dimensões - boolean
		private $plabirinto = null; // Labirinto
	
		function solve ($labirinto) { 
			// Retorna lista de células
			for ($xLin = 0; $xLin < $labirinto->linhas; $xLin++) {
				for ($xCol = 0; $xCol < $labirinto->colunas; $xCol++) {
					$this->visitadas[$xLin][$xCol] = false;
				}
			}
			$this->plabirinto = $labirinto;
			$this->corrente = $labirinto->celulas[0][0];
			array_push($this->pilha,$this->corrente);
			$this->procurar();
			
			foreach($this->pilha as $celpilha) {
				array_push($this->caminho, $celpilha);
			}
			
			return $this->caminho;
		}
	
		function procurar() {
			$buffer = null; // Celula
			while (count($this->pilha) > 0) {
				$this->visitadas[$this->corrente->y][$this->corrente->x] = true;
				if ($this->corrente->fim) {
					return;
				}
	
				$proxima = null;
	
				for ($parede = 0; $parede < 4; $parede++) {
					if (!$this->corrente->paredes[$parede]) {
						if ($parede == DIRECOES::NORTE && $this->corrente->y == 0) {
							continue;
						}
						$coluna = $this->corrente->x + $this->incrementos[$parede][0];
						$linha = $this->corrente->y + $this->incrementos[$parede][1];
	
						if ($coluna >= $this->plabirinto->colunas || $linha >= $this->plabirinto->linhas) {
							continue;
						}
	
						if ($this->visitadas[$linha][$coluna]) {
							continue;
						}
	
						$proxima = $this->plabirinto->celulas[$linha][$coluna];
						if ($buffer != null) {
							array_push($this->pilha, $buffer);
							$buffer = null;
						}
						array_push($this->pilha,$proxima);
	
						$this->corrente = $proxima;
						break;
					}
				}
	
				if ($proxima == null) {
					$this->corrente = array_pop($this->pilha);
					$buffer = $this->corrente;
				}
			}
		}
	}
	
	
?>