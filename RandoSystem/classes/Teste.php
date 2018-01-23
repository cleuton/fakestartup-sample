<?php

class Teste {
	public $celulas = array();
	function __construct($linhas, $colunas) {
		for ($i = 0; $i < $linhas; $i++) {
			for ($j = 0; $j < $colunas; $j++) {
				$this->celulas[$i][$j] = "[" . $i . "," . $j . "]";
			}
		}
   	}
   	
    function displayVar() {
        foreach ($this->celulas as $celula) {
	        foreach ($celula as $c) {
		        echo "<br/>Celula: " . $c; 
	        }
        }
    }   	
}

?>