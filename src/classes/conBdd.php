<?php

	class conBDD{
		
		const HOST 	= "localhost";
		const USER 	= "root";
		const PASS 	= "toto";
		const DB 	= "darim";
		public $connexion;
		private static $link;
		private static $instance = null;
		
		public function __construct(){
			$options = array(
			    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			);
			ini_set('error_reporting', E_ALL|E_STRICT);
			ini_set('display_errors', 1);
			try
			{
				$this->connexion = new PDO('mysql:host='.self::HOST.';dbname='.self::DB, self::USER, self::PASS, $options);
			}
			  
			catch(Exception $e)
			{
				echo 'Erreur : '.$e->getMessage().'<br />';
				echo 'N° : '.$e->getCode();
			}
		}
	}
	
?>