<?php
/**
 +---------------------------------------------------------------------------+
 +--------------- ( IDToolkit - Wordpress vulnerability scanner  )-----------+
 +---------------------------------------------------------------------------+
 | @author         : SHOR7CUT ( BUG7SEC )
 | @author URL     : http://www.facebook.com/bug7sec
 | @author Email   : zhero2day@gmail.com
 | @Spesial        : https://bug7sec.github.io/idtoolkit
 +---------------------------------------------------------------------------+
 + Description Indonesia :  IDToolkit merupakan tools untuk mencari sebuah kelemahan 
 + pada wordpress dengan IDToolkit semua plugin , theme dan version wordpress
 + yang rentan dapat diketahui. toolkit ini sengaja dibuat untuk para pentester.
 *****************************************************************************
 * [t.o.s] Semua orang dapat mengedit seluruh file didalam folder DB , semua orang 
 * dapat memperbarui toolkit ini dengan catatan tidak menghilangkan 
 * author name , url , greatz , spesial dan nama asli dari file ini. 
 * kami tidak bertanggung jawab atas kerugian yang ditimbulkan oleh penguna 
 * toolkit ini. 
 *****************************************************************************
 | LICENSE       : Creative Commons - Atribusi 4.0 Internasional
 | LICENSE URL   : https://creativecommons.org/licenses/by/4.0/legalcode.id
 | Copyright (c) 2016 BUG7SEC. All rights reserved.
 +---------------------------------------------------------------------------+
**/
$config  = new ShcConfig;
$config->setVersion("1.0.2");
$config->setLastUpdate("27/08/2016");

class ShcConfig
{
	function pesan($value){
		echo "[IDTookit] ".$value."\r\n";
	}
	function setVersion($value){
		return $this->ver 		= $value;
	}
	function setLastUpdate($value){
		return $this->last 		= $value;
	}
	function dbCount($file,$plode){
		$file = file_get_contents("DB/".$file);
		$file = explode($plode, $file);
		return count($file);
	}
	function updateGET($url){
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, ShcAction::useragent() );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."idtoolkit-cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."idtoolkit-cookies.txt");
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        $data = curl_exec($ch);
        return $data;
	}
	function checkUpdate(){
		$this->pesan("[UPDATE] Check update...");
		$update = json_decode($this->updateGET("https://bug7sec.github.io/idtoolkit/update.json"),true);
		if( $this->ver >= $update['version'] ){
		$this->pesan("[UPDATE] your version ".$this->ver." no latest version");
		}else{
			$this->pesan("[UPDATE] new version ".$update['version']." , your version ".$this->ver);
			$this->pesan("[Quest] Are you sure ? want to update now? type y to continue : ");
			echo "-> ";
			$handle = fopen ("php://stdin","r");
			$line = fgets($handle);
			if(trim($line) != 'y'){
			    $this->pesan("[UPDATE] Update has been close");
			    exit;
			}
			$this->pesan("[UPDATE] Pleas wait... ");
			$this->pesan("[UPDATE] Check Required Folder : ".$update['version']);
			foreach ($update['required']['dir'] as $key => $value) {
				$this->pesan("-> ".$value);
				sleep(1);
			}
			$this->pesan("[UPDATE] Check Required File   : ".$update['version']);
			foreach ($update['required']['file'] as $key => $value) {
				$this->pesan("-> ".$key." | ".$value);
				sleep(1);
			}
			foreach ($update['required']['dir'] as $key => $value) {
				if( rmdir($value) ){
					$this->pesan("-> Remove Dir ".$value);
				}
				if( mkdir($value, 0777, true) ){
						$this->pesan("-> Generate Dir :  ".$value." +Success");
					if( chmod($value, 0777) ){
						$this->pesan("-> Chmod Dir ->  ".$value." +Success");
					}else{
						$this->pesan("-> Chmod Dir ->  ".$value." +failed");
					}
				}else{
					$this->pesan("-> Generate Dir :  ".$value." +failed");
				}
				sleep(1);
			}
			foreach ($update['required']['file'] as $key => $value) {
				if( rmdir($value) ){
					$this->pesan("-> Remove File ".$value);
				}
					$url = $update['url'].$value;
				if( file_put_contents( $value, file_get_contents($url) ) ){
					$this->pesan("-> Download :  ".$key." | ".$url." [OK]");
				}else{
					$this->pesan("-> Download :  ".$key." | ".$url." [FAIL]");
				}
			}
				$this->pesan("[UPDATE] Update Success ".date("Y-m-d h:i:sa"));
		}
	}
	function help(){
		$filename = "[IDToolkit] php ".basename($_SERVER["SCRIPT_FILENAME"], '.php ');
		$arrayName = array(
			$filename.' --help' 							=> ' | show help', 
			$filename.' --url=http://target.com --test' 	=> ' | test single target',
			$filename.' --from-db --filter-wp'		 	=> ' | filter target to wp-site.txt from db (search.txt)',
			$filename.' --from-db --test' 				=> ' | test with db',
			$filename.' --list=target.txt --test' 		=> ' | test with list',
			$filename.' --list=target.txt --filter-wp' 	=> ' | filter target to wp-site.txt from manual list',
			$filename.' --dork="{dork}" --filter' 		=> ' | grab target and filter wp',
			$filename.' --dork="{dork}" --no-filter' 	=> ' | grab target only with bing (search.txt)',
			$filename.' --dork="{dork}" --full'	 		=> ' | grab target , filter wp and test ',
			$filename.' --clear-engine'	 				=> ' | grab target , filter wp and test ',
		);
		foreach ($arrayName as $key => $value) {
			echo $key.$value."\r\n";
		}
	}
	function cover(){
		echo "-------------------------------------------------\r\n";
		echo "-- IDToolkit - Wordpress vulnerability scanner --\r\n";
		echo "-------------------------------------------------\r\n";
		echo "-[> DB useragent : ".$this->dbCount("user-agent.db","\r\n")." useragent\r\n";
		echo "-[> DB Plugin    : ".$this->dbCount("wp-plugin.db","|")." wp plugin\r\n";
		echo "-[> DB Themes    : ".$this->dbCount("wp-theme.db","|")." wp themes\r\n";
		echo "-[> Version      : ".$this->ver."\r\n";
		echo "-[> Last Update  : ".$this->last."\r\n";
		echo "-------------------------------------------------\r\n";
		echo "[Command] php ".basename($_SERVER["SCRIPT_FILENAME"], '.php').".php --help\r\n\n";
	}
	function checkPackage(){
		$required = array('user-agent.db','wp-plugin.db','wp-shell.db','wp-theme.db','wp-upload.db','wp-version.db');
		foreach ($required as $key => $package) {
			$db = "DB/".$package;
			if(!file_exists($db)){
				echo ":( error package...pleas update\r\n";
				exit();
			}
		}
		if(!defined('STDIN') ){
			echo "<h1>Only Running from CLI </h1>";
			exit();
		}
		unlink("idtoolkit-cookies.txt");
	}
	function checkEngine(){
		$files = array(
			'wordpress target (db)' 		=> 'engine/result/wp-site.txt' , 
			'result search (db)'			=> 'engine/result/search.txt' , 
			'temp search unique (db)' 		=> 'engine/temp/search.txt' , 
			'temp search no-unique (db)'	=> 'engine/temp/search-temp.txt' , 
		);
		echo "<?--------[ INFO DB ]--------?>\r\n";
		foreach ($files as $key => $values) {
			$count =  count(explode("\r\n", file_get_contents($values)));
			$count = ($count == 1 ? 0 : $count);
			echo "|-> {$key} [ ".$count." ]\r\n";
		}
	}

	function clearEngine(){
		$files = array(
			'wordpress target (db)' 		=> 'engine/result/wp-site.txt' , 
			'result search (db)'			=> 'engine/result/search.txt' , 
			'temp search unique (db)' 		=> 'engine/temp/search.txt' , 
			'temp search no-unique (db)'	=> 'engine/temp/search-temp.txt' , 
		);
		foreach ($files as $key => $values) {
			unlink($values);
		}
		ShcConfig::checkEngine();
	}

	function run(){
		$this->cover();
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