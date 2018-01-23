<?php

require 'classes/Celula.php';

class Labirinto {
	public $linhas = 0;
	public $colunas = 0;
	public $celulas = array();
	public $celulaInicial = null;
	public $celulaFinal = null;
	public $valido = false;
	private $corrente = null;
	private $proxima = null;
	private $qtdTotal = 0;
	private $qtdVisitadas = 0;
	private $pilha = array();
	
	function __construct($qtdlinhas, $qtdcolunas) {
		$this->linhas = $qtdlinhas;
		$this->colunas = $qtdcolunas;
		$this->inicializar();
	}
	
	function inicializar() {
		// Teste para ver se gerou um "dead-end":
		$contador = 0;
		while ($contador < 4) {
			$this->celulas = array();
			for ($i = 0; $i < $this->linhas; $i++) {
				for ($j = 0; $j < $this->colunas; $j++) {
					$this->celulas[$i][$j] = new Celula();
					$this->celulas[$i][$j]->y = $i;
					$this->celulas[$i][$j]->x = $j;
					$this->celulas[$i][$j]->inicio = false;
					$this->celulas[$i][$j]->fim = false;
					$this->celulas[$i][$j]->visitada = false;
				}
			}
			$this->celulas[0][0]->inicio = true;
			$this->celulas[$this->linhas - 1][$this->colunas - 1]->fim = true;
		
		
			$contador = $contador + 1;
			$this->pilha = array();
			$this->qtdVisitadas = 0;
			$this->criar();
			if ((!$this->fechada($this->celulas[1][1])) && 
				(!$this->fechada($this->celulas[$this->linhas - 2][$this->colunas - 2]))) {
				break;
			}
		}
		// Não conseguiu gerar um labirinto ok:
		if ($contador < 4) {
			$this->valido = true;
		}
		
	}

	function fechada($xcell) {
		$retorno = false;
		if ($xcell->paredes[DIRECOES::NORTE] == true && 
			$xcell->paredes[DIRECOES::SUL] == true &&
			$xcell->paredes[DIRECOES::LESTE] == true &&
			$xcell->paredes[DIRECOES::OESTE] == true) {
			$retorno = true;
		}
		return $retorno;
	}
	
	function criar() {
		$this->qtdTotal = $this->linhas * $this->colunas;
		$linha = mt_rand(0,$this->linhas - 1);
		$coluna = mt_rand(0,$this->colunas - 1);
		$this->corrente = $this->celulas[$linha][$coluna];
		$this->corrente->visitada = true;
		$this->proxima = $this->pegarVizinha($this->corrente);
		$this->proxima->visitada = true;
		$this->quebrarParedes($this->corrente, $this->proxima);
		array_push($this->pilha, $this->corrente);
		$this->qtdVisitadas++;
		$this->corrente = $this->proxima;
		$this->processaCelula();
	}

	function processaCelula() {
		
		while (true) {
			if (count($this->pilha) > 0) {//if the maze is incomplete || account for stack starting empty
				if ($this->isDeadEnd($this->corrente) || $this->corrente->fim || $this->corrente->inicio) { //if c has no unvisited neighbors, or it's the start/end cell, start backtracking...
					$this->proxima = array_pop($this->pilha);//by popping the last cell off the stack...
					$this->corrente = $this->proxima;//and recursing with it as c
				} else { // if there are unvisited neighbors...
					$this->proxima = $this->pegarVizinha($this->corrente);
					$this->quebrarParedes($this->corrente, $this->proxima);
					array_push($this->pilha, $this->corrente);
					$this->proxima->visitada = true;
					$this->qtdVisitadas++;
					$this->corrente = $this->proxima;
				}
			} else { //maze is finished
				$this->celulas[0][0]->paredes[DIRECOES::NORTE] = false;
				$this->celulas[$this->linhas - 1][$this->colunas - 1]->paredes[DIRECOES::SUL] = false;
				return; //stop looping the draw() method
			}			
		}


	}
	
	function isDeadEnd($celula) {
		
		if ($celula->y > 0) {
			// Vizinha norte
			if ($this->celulas[$celula->y - 1][$celula->x]->visitada == false) {
				return false;
			}
		}
		if ($celula->y < ($this->linhas - 1)) {
			// Vizinha sul
			if ($this->celulas[$celula->y + 1][$celula->x]->visitada == false) {
				return false;
			}
		}
		if ($celula->x > 0) {
			// Vizinha oeste
			if ($this->celulas[$celula->y][$celula->x - 1]->visitada == false) {
				return false;
			}
		}
		if ($celula->x < ($this->colunas - 1)) {
			// Vizinha leste
			if ($this->celulas[$celula->y][$celula->x + 1]->visitada == false) {
				return false;
			}	
		}
		
		return true;
	}

	function quebrarParedes($c1, $c2) {
		if ($c1->x > $c2->x) {
			// c1 est‡ ˆ direita de c2
			$c1->paredes[DIRECOES::OESTE] = false;
			$c2->paredes[DIRECOES::LESTE] = false;
			return;
		}
		if ($c1->x < $c2->x) {
			// c1 est‡ ˆ esquerda de c2
			$c1->paredes[DIRECOES::LESTE] = false;
			$c2->paredes[DIRECOES::OESTE] = false;
			return;			
		}
		if ($c1->y > $c2->y) {
			// c1 est‡ abaixo de c2
			$c1->paredes[DIRECOES::NORTE] = false;
			$c2->paredes[DIRECOES::SUL] = false;
			return;			
		}
		if ($c1->y < $c2->y) {
			// c1 est‡ acima de c2
			$c1->paredes[DIRECOES::SUL] = false;
			$c2->paredes[DIRECOES::NORTE] = false;
			return;			
		}
	}

	function pegarVizinha($celula) {
		$procurar = true;
		$cel = null;
		while ($procurar) {
			$vizinha = mt_rand(0,3);
			switch($vizinha) {
			case DIRECOES::NORTE: // NORTE
				if ($celula->y > 0) {
					$cel = $this->celulas[$celula->y - 1][$celula->x];						
				}
				break;
			case DIRECOES::SUL: // SUL
				if ($celula->y < ($this->linhas - 1)) {
					$cel = $this->celulas[$celula->y + 1][$celula->x];
				}
				break;
			case DIRECOES::LESTE: // LESTE
				if ($celula->x < ($this->linhas - 1)) {
					$cel = $this->celulas[$celula->y][$celula->x + 1];
				}
				break;
			case DIRECOES::OESTE: // OESTE
				if ($celula->x > 0) {
					$cel = $this->celulas[$celula->y][$celula->x - 1];
				}
			}
			if ($cel != null && !$cel->visitada) {
				$procurar = false;
			}
		}

		return $cel;
	}
	
}

?>