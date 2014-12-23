<?php

	class conBDD{
		
		public $connexion;
		private static $link;
		private static $instance = null;
		
		public function __construct($host, $port, $user, $pass, $db){
			$options = array(
			    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			);
			ini_set('error_reporting', E_ALL|E_STRICT);
			ini_set('display_errors', 1);
			try
			{
				$this->connexion = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port.';', $user, $pass, $options);
			}
			  
			catch(Exception $e)
			{
				echo 'Erreur : '.$e->getMessage().'<br />';
				echo 'Numero : '.$e->getCode();
			}
		}
	}
	
?>