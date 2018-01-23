<?php

require_once 'classes/TipoNavegador.php';
require_once 'classes/GameLogic.php';

class Navegador {
	public static function analisar() {
		$tipo = new TipoNavegador();
		$tipo->ok = false;
		$tipo->nome = "outro";
		$tipo->versao = "0";
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		// Check for IE:
		$pos = stripos($ua, "MSIE");
		if ($pos != false) {
			$tipo->nome = "Microsoft Internet Explorer";
			// Verificar se é o 9.0
			$posver = strpos($ua,".", $pos);
			$tam = $posver - ($pos + 5);
			$versao = substr($ua, $pos + 5, $tam);
			$tipo->versao = $versao;
			if ($versao >= 9) {
				$tipo->ok = true;
			}
		}
		else {
			// Check for Firefox:
			$pos = stripos($ua, "Firefox");
			if ($pos != false) {
				$tipo->nome = "Mozilla Firefox";
				// Verificar se é o 9 ou superior
				$posver = strpos($ua,".", $pos);
				$tam = $posver - ($pos + 8);
				$versao = substr($ua, $pos + 8, $tam);
				$tipo->versao = $versao;
				if ($versao >= 9) {
					$tipo->ok = true;
				}
			}
			else {
				// Check for Chrome:
				$pos = stripos($ua, "Chrome");
				if ($pos != false) {
					$tipo->nome = "Google Chrome";
					// Verificar se é o 14 ou superior
					$posver = strpos($ua,".", $pos);
					$tam =  $posver - ($pos + 7);
					$versao = substr($ua, $pos + 7, $tam);
					$tipo->versao = $versao;
					if ($versao >= 14) {
						$tipo->ok = true;
					}
				}
				else {
					// Check for Safari:
					$pos = stripos($ua, "Safari");
					if ($pos != false) {
						$tipo->nome = "Apple Safari";
						// Verificar se é o 5 ou superior
						$pos = strpos($ua, "Version/");
						$posver = strpos($ua,".", $pos);
						$tam = $posver - ($pos + 8);
						$versao = substr($ua, $pos + 8, $tam);
						$tipo->versao = $versao;
						if ($versao >= 5) {
							$tipo->ok = true;
						}
					}
				}
			}
		}
		
		
		return $tipo;
	}
}

?>