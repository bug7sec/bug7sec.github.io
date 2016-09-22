<?php
class Action
{
	function help(){
		echo "\r\n-------------------------------------------------------------\r\n\n";
		$array = array(
			' --dork={dork} --full-patch' 	=> 'Search Victim Full Patch', 
			' --dork={dork} --non-patch'  	=> 'Search Victim Domain Only', 
			' --update'  					=> 'Update Mini toolkit', 
		);
		foreach ($array as $command => $desc) {
			Action::Msg($command."  | ".$desc."\r\n");
		}
	}
	function cover($version){
		echo "     _____ _____ _____ ___ _____ _____ _____         \r\n";
		echo "    | __  |  |  |   __|_  |   __|   __|     | --help \r\n";
		echo "    | __ -|  |  |  |  | | |__   |   __|   --|        \r\n";
		echo "    |_____|_____|_____| | |_____|_____|_____|        \r\n";
		echo "       Codename : Larva | | Version : ".$version."           \r\n";
	}
	function Msg($value){
		echo "[BUG7SEC]".$value;
	}
	function NgeCurl($url , $post=null){
		$ch = curl_init($url);
        if($post != null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; U; CPU iPhone OS 8_3_3 like Mac OS X; en-SG) AppleWebKit/537.25 (KHTML, like Gecko) Version/7.0 Mobile/8C3 Safari/6533.18.1");
        curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'temp/'."cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'temp/'."cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
    	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	return curl_exec($ch);
        curl_close($ch);
	}
	function Ngesave($name , $data ){
		$myfile = fopen($name, "a+") or die("Unable to open file!");
      	fwrite($myfile, $data);
      	fclose($myfile);
	}
	function Debug( $data ){
		unlink("debug.html");
		$myfile = fopen("debug.html", "a+") or die("Unable to open file!");
      	fwrite($myfile, $data);
      	fclose($myfile);
	}
	function filterDomain($str){
		$re = "/:\\/\\//"; 
		preg_match_all($re, $str, $matches);
		if( $matches[0][0] ){
			if(!preg_match("/live|msn|bing|microsoft|google|blogspot/",$str)){
				return $str."\r\n";
			}
		}
	}
	function Clean($saves,$option){
		$file  = file_get_contents("report/temp/result.txt");
		$file  = explode("\r\n", $file);
		$file  = array_unique($file);
		foreach ($file as $key => $sites) {
			if( $option ){
				Action::Ngesave($saves,$sites."\r\n");
			}else{
				Action::Ngesave($saves,"http://".parse_url($sites, PHP_URL_HOST)."\r\n");
			}
		}
		unlink("report/temp/result.txt");
		return true;
	}

	/********* UPDATE ***********************/
	function Update($version){
		echo "\r\n\n-------------------------------------------------------------\r\n";
		$CheckJson = json_decode( Action::NgeCurl("https://bug7sec.github.io/bug7sec-mini/update.json") ,true );
		if( $version >= $CheckJson['version'] ){
			Action::Msg("[UPDATE] Your version ".$version." no latest version");
		}else{
			Action::Msg("[UPDATE] Your version ".$version." , update to ".$CheckJson['version']."\r\n");
			Action::Msg("[UPDATE] Create directory required ...\r\n");
			foreach ($CheckJson[dir] as $dirs) {
				Action::Msg("[Create] ".$dirs." directory\r\n");
				mkdir($dirs, 0755);
			}
			Action::Msg("[UPDATE] Replace & Create New File package ...\r\n");
			foreach ($CheckJson[package] as $value) {
				if( file_put_contents($value , Action::NgeCurl($CheckJson[repo].$value)) ){
					Action::Msg("[Create] ".$value." files\r\n");
				}else{
					Action::Msg("[REPORT] Pleas report bug https://github.com/bug7sec/bug7sec.github.io/tree/master/bug7sec-mini\r\n");
					exit();
				}
			}
		}
	}

}