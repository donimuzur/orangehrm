<?php
class Conf {

	var $smtphost;
	var $dbhost;
	var $dbport;
	var $dbname;
	var $dbuser;
	var $version;

	function __construct() {

		$this->dbhost	= 'server2';
		$this->dbport 	= '3306';
		$this->dbname	= 'orangehrm_mysql';
		$this->dbuser	= 'root';
		$this->dbpass	= 'Polowijo605421';
		$this->version = '4.4';

		$this->emailConfiguration = dirname(__FILE__).'mailConf.php';
		$this->errorLog =  realpath(dirname(__FILE__).'/../logs/').'/';
	}
}
?>
