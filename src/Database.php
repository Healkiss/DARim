<?php
public class Database
{
	public function __construct($parameters)
	{
        $this->driver = $parameters['db_driver'];
        $this->host = $parameters['db_host'];
        $this->user = $parameters['db_user'];
        $this->password = $parameters['db_password'];
        $this->name = $parameters['db_name'];
        try{
            error_reporting(E_ALL ^ E_WARNING); // Don't show php errors concerning DB Connection
            $database =  new PDO($this->driver.':host='.$this->host.';dbname='.$this->name, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            error_reporting(E_ALL ^ E_NOTICE); // Turn error reporting back to default
            return $database;
        }catch(PDOexeption $e){
            echo 'La base de donn&eacute; n est pas disponible<br>';
            echo 'Erreur:'.$e.getMessage().'<br>';
            echo 'Numero:'.$e.getCode();
        }
        return null;
	}
}