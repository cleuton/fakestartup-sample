<?php

require_once 'classes/Level.php';
require_once 'classes/Labirinto.php';
require_once 'classes/DB_Manager.php';

	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	}

class GameLogic {

	public $levels = array();
	public $levelQtd = 0;
	
	function __construct() {
		
		
		$dbm = new DB_Manager();
		$comando = "select * from levels;";
		$resultadoQuery = $dbm->query($comando);
		if ($resultadoQuery) {
			while (($linha = $dbm->fetch_array($resultadoQuery))) {
				$level = new Level();
				$level->number = $linha[0] . "";
				$level->title = $linha[1] . "";
				$level->games = $linha[2] . "";
				$level->average = $linha[5] . "";
				$level->cols = $linha[4] . "";
				$level->rows = $linha[3] . "";
				$level->picture = $linha[6] . "";
				
				array_push($this->levels, $level);
				$this->levelQtd++;
			}
		}

		
		$_SESSION['levels'] = $this->levels;
		
		$this->levels = $_SESSION['levels'];
		
	}
	
	function getMaze($dlevel) {
		$maze = null;
		if (isset($dlevel)) {
			if ($dlevel <= $_SESSION['gamelevel']) {
				foreach ($_SESSION['levels'] as $level) {
					if ($level->number == $dlevel) {
						$maze = new Labirinto(
								$level->rows,
								$level->cols
								);
						break;	
					}
				}
			}
		}
		return $maze;
	}
	
	function recordScore($segundos) {
		$dbm = new DB_Manager();
		$userId = $_SESSION['userid'];
		$gameLevel = $_SESSION['playedlevel'];
		$level = null;
		
		foreach ($_SESSION['levels'] as $l) {
			if ($l->number == $gameLevel) {
				$level = $l;
				break;
			}
		}
		
		$retorno = false; // Se mudou de n�vel, retorna true
		
		
		$comando = "INSERT INTO user_games (id, level_played, seconds) values " .
				"(" . $userId .  ", " . $gameLevel . ", " . $segundos . ");";
		$dbm->query($comando);
		
		// Vamos manter sempre os �ltimos resultados de acordo com as regras do n�vel
		$comando = "delete from user_games where id = ".
					$userId .
					" and level_played = " . 
					$gameLevel .
      					" and data in " .
      					" (select data from user_games where id = " . 
      					$userId . 
      					" and level_played = " . 
					$gameLevel . 
					" order by data desc offset " . $level->games . ");";
		
		// Verificamos se o usu�rio mudou de n�vel
		$dbm->query($comando);
		
		// Verifica se o usu�rio estava jogando em seu n�vel
		if ($_SESSION['playedlevel'] == $_SESSION['gamelevel']) {			
			$retorno = $this->verificaMudanca();
		}
		return $retorno;
	}
	
	function verificaMudanca() {
		$passou = false;
		$dbm = new DB_Manager();
		$userId = $_SESSION['userid'];
		$gameLevel = $_SESSION['gamelevel'];
		
		$level = null;
		
		foreach ($_SESSION['levels'] as $l) {
			if ($l->number == $gameLevel) {
				$level = $l;
				break;
			}
		}
		
		
		// Existe o n�vel final, que n�o tem m�dia...
		if ($level->average > 0) {
			$comando = "select count(id) as qtde, avg(seconds) as media from user_games where id = " .
					$userId ." and level_played = " . $gameLevel .";";
			$resultadoQuery = $dbm->query($comando);
			if ($resultadoQuery) {
				if (($linha = $dbm->fetch_array($resultadoQuery))) {
					// Assmume que a diferen�a entre n�veis � 1 unidade!
					// Verifica se o usu�rio atingiu
					if ($linha[0] >= $level->games && $linha[1] <= $level->average) {
						$comando =  "BEGIN TRANSACTION; " .
								"update game_user set nivel = nivel + 1 where id = " .
								$userId . "; " .
								"COMMIT; ";
						$resultadoQuery = $dbm->query($comando);
						$passou = true;
						$_SESSION['gamelevel'] += 1;
					}
				}
			}			
		}
		
		return $passou;
	}
	
	function logon($fbid) {
		$dbm = new DB_Manager();
		$comando = "select * from game_user where fbid = " . $fbid . ";";
		$resultadoQuery = $dbm->query($comando);
		if ($resultadoQuery) {
			$linha = $dbm->fetch_array($resultadoQuery);
			$_SESSION['userid'] = $linha[0];
			$_SESSION['gamelevel'] = $linha[2];
			$_SESSION['userlocale'] = "pt";      // aten��o! ******
			Echo '****** ' . $linha[0];
			error_log('userID:' . $linha[0]);
		} else {
			$msg = '*** ERRO: ' . $resultadoQuery;
			error_log($msg,0);
		}
	}
	
	function verificarRecords() {
		$dbm = new DB_Manager();
		$retorno = false;
		$ultimoScore = 0;
		
		// Primeiro, verificamos o score contra os records individuais do usu�rio
		
		$comando = "select seconds from user_games where id = " . $_SESSION['userid'] . " and level_played = " .  $_SESSION['playedlevel'] .
					" order by data desc limit 1;";
		$resultadoQuery = $dbm->query($comando);
		if ($resultadoQuery) {
			if (($linha = $dbm->fetch_array($resultadoQuery))) {
				$ultimoScore = $linha[0];
			}
		}
		$comando = "select seconds from user_records where id = " . $_SESSION['userid'] . " and level_played = " .  $_SESSION['playedlevel'] . 
					" order by seconds desc limit 1;";
		$resultadoQuery = $dbm->query($comando);
		if ($resultadoQuery) {
			if (($linha = $dbm->fetch_array($resultadoQuery))) {
				if ($linha[0] > $ultimoScore) {
					$comando = "update user_records set seconds = " . $ultimoScore .
					" where id = " . $_SESSION['userid'] . " and level_played = " .  $_SESSION['playedlevel'] . ";";
					$dbm->query($comando);
					$retorno = true;
				}
			}
			else {
				$comando = "insert into user_records (id, level_played, seconds) values (" . $_SESSION['userid'] .
				", " . $_SESSION['playedlevel'] . ", " . $ultimoScore . ");";
				$dbm->query($comando);
				$retorno = true;
			}
				
		}
		
		// Agora, verificamos o score contra os records totais
		
		$comando = "select seconds from absolute_records where level = " .  $_SESSION['playedlevel'] .
		" limit 1;";
		
		$resultadoQuery = $dbm->query($comando);
		if ($resultadoQuery) {
			if (($linha = $dbm->fetch_array($resultadoQuery))) {
				if ($linha[0] > $ultimoScore) {
					$comando = "update absolute_records set seconds = " . $ultimoScore .
					", id = " . $_SESSION['userid'] . " where level = " .  $_SESSION['playedlevel'] . ";";
					$dbm->query($comando);
					$retorno = true;
				}
			}
			else {
				$comando = "insert into absolute_records (level, id, seconds) values (" . 
				$_SESSION['playedlevel'] . ", " .
				$_SESSION['userid'] . ", " .
				$ultimoScore . ");";
				$dbm->query($comando);
				$retorno = true;
			}
		}
		
		return $retorno;
	}
	
	function loadapc() {
		
		if (!isset($_SESSION['i18n'])) {
			$dbm = new DB_Manager();
			$comando = "select * from i18n where locale = '" .  $_SESSION['userlocale'] . "';";
			$i18n = array();
			$resultadoQuery = $dbm->query($comando);
			if ($resultadoQuery) {
				while (($linha = $dbm->fetch_array($resultadoQuery))) {
					$i18n[$linha[0]] = $linha[2];			
				}
			}
			$_SESSION['i18n'] = $i18n;
		}
		
	}
	
	function translate($chave) {
		$retorno = "*ERROR*";
		if (array_key_exists($chave,$_SESSION['i18n'])) {
			$retorno = $_SESSION['i18n'][$chave];
		}
		return $retorno;
	}
}

?>