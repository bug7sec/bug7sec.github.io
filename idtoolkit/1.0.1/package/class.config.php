<?php
/**
 +---------------------------------------------------------------------------+
 | @author       : SHOR7CUT
 | @author URL   : http://www.facebook.com/bug7sec
 | @author Email : zhero2day@gmail.com
 | @Spesial      : Tuban Cyber Team , Bug7sec Team , LinuxSec , IDCA
 |                 Defacer Tersakiti , SystemR0ot Team , Sec7or Team 
 +---------------------------------------------------------------------------+
 +--------------- (             HELLO THE WORD            )------------------+
 +---------------------------------------------------------------------------+
 | LICENSE       : Creative Commons - Atribusi 4.0 Internasional
 | LICENSE URL   : https://creativecommons.org/licenses/by/4.0/legalcode.id
 | Copyright (c) 2016 BUG7SEC. All rights reserved.
 +---------------------------------------------------------------------------+
**/
error_reporting(0);
$config  = new ShcConfig;
$config->setAutoCheck(false);
$config->setVersion("1.0.1");
$config->setLastUpdate("25/08/2015");
$config->setAutoUpdate(true);
$config->run();
class ShcConfig
{
	function dbCount($file,$plode){
		$file = file_get_contents("DB/".$file);
		$file = explode($plode, $file);
		return count($file);
	}
	function cover(){
		echo "-------------------------------------------------\r\n";
		echo "-- IDToolkit - Wordpress vulnerability scanner --\r\n";
		echo "-------------------------------------------------\r\n";
		echo "-[> DB Userganet : ".$this->dbCount("user-agent.db","\r\n")." useragent\r\n";
		echo "-[> DB Plugin    : ".$this->dbCount("wp-plugin.db","|")." wp plugin\r\n";
		echo "-[> DB Themes    : ".$this->dbCount("wp-theme.db","|")."  wp themes\r\n";
		echo "-[> Version      : ".$this->ver."\r\n";
		echo "-[> Last Update  : ".$this->last."r\n";
		echo "-------------------------------------------------\r\n";
		echo "[Command] php ".basename($_SERVER["SCRIPT_FILENAME"], '.php').".php --help\r\n\n";
	}
	function setVersion($value){
		return $this->ver 		= $value;
	}
	function setLastUpdate($value){
		return $this->last 		= $value;
	}
	function setAutoUpdate($value){
		return $this->update 	= $value;
	}
	function setAutoCheck($value){
		return $this->check 	= $value;
	}
	function checkrun(){
		if(!defined('STDIN') ){
			echo "<h1>Only Running from CLI </h1>";
			exit();
		}
	}
	function checkPackage(){
		$required = array('user-agent.db','wp-plugin.db','wp-shell.db','wp-theme.db','wp-upload.db','wp-version.db');
		foreach ($required as $key => $package) {
			$db = "DB/".$package;
			if(!file_exists($db)){
				echo ":( error package\r\n";
				exit();
			}
		}
	}
	function run(){
		$this->checkPackage();
		$this->cover();
		$this->checkrun();
	}
}


function arguments($argv) { 
	    $_ARG = array(); 
	    foreach ($argv as $arg) { 
	      if (ereg('--([^=]+)=(.*)',$arg,$reg)) { 
	        $_ARG[$reg[1]] = $reg[2]; 
	      } elseif(ereg('^-([a-zA-Z0-9])',$arg,$reg)) { 
	            $_ARG[$reg[1]] = 'true'; 
	      } else { 
	            $_ARG['input'][]=$arg; 
	      } 
	    } 
  	return $_ARG; 
}
?>