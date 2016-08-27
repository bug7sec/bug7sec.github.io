<?php
/**
 +---------------------------------------------------------------------------+
 +--------------- ( IDToolkit - Wordpress vulnerability scanner  )-----------+
 +---------------------------------------------------------------------------+
 | @author         : SHOR7CUT ( BUG7SEC )
 | @author URL     : http://www.facebook.com/bug7sec
 | @author Email   : zhero2day@gmail.com
 | @Greatz         : Tuban Cyber Team  , Bug7sec Team    , LinuxSec , IDCA
 |                   Defacer Tersakiti , SystemR0ot Team , Sec7or Team
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
class ShcEngine 
{
	
	function search($value)
	{
		return $this->dork = $value;
	}
	function CurlPost($url, $post = false){
		header('content-type: application/json; charset=utf-8');
		$cookies = "cookie.txt";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, ShcAction::useragent());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookies );
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies );
		if($post !== false){
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($post));
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		$data = curl_exec($ch);
		unlink($cookies);
		curl_close($ch);
		return $data;
	}
	function filter($value,$data){
		switch ($value) {
			case 'bing':
				$data = str_replace("<cite>", 		"", $data);
		    	$data = str_replace("</cite>", 		"", $data);
		    	$data = str_replace("<strong>", 	"", $data);
		    	$data = str_replace("</strong>", 	"", $data);
		    	$data = str_replace("https://", 	"", $data);
		    	$data = str_replace("http://", 		"", $data);
		    	$data = str_replace("www.", 		"", $data);
		    	return $data;
			break;
		}
	}
	function saves($nama,$data){
      $myfile = fopen($nama, "a+") or die("Unable to open file!");
      fwrite($myfile, $data);
      fclose($myfile);
   	}
	function bingDomain($link){
		return $this->filter(bing,parse_url("http://".$link, PHP_URL_HOST));
	}
	function pesan($pesan){
		echo "[IDTookit][".date("H:i:s")."] ".$pesan."\n";
	}
	function bing()
	{
		header('content-type: application/json; charset=utf-8');
		$sub = array("","ca","net","co.uk","co","br","be","nl","uk","it","es","de","no","dk","se","ch","ru","jp","cn","kr","mx","ar","cl","au");
		foreach ($sub as $key => $domain) {
			if($domain == ""){
					$this->pesan("[BING] Search Bing Global : ".$this->dork);
				}else{
					$this->pesan("[BING] Search Bing ".$domain." :  ".$this->dork);
			}
			$shc = 1000;
			for($i=0; $i<=$shc; $i+=10){
				$query = array('q' => $this->dork ,'go' => 'Submit','qs' => 'n','pq' => $this->dork,'sc' => '0-9','cc' => $domain ,'sp' => '-1','sk' => '','cvid' => 'A075046B151E4255BE6ABA9FFA67457E','first' => $i ,'FORM' => 'PERE');
				$result = $this->CurlPost("http://www.bing.com/search?".http_build_query($query));
				$re = "/class=\"b_no\"/"; 
				preg_match($re, $result, $matches);
				preg_match_all('/<a href=\"?http:\/\/([^\"]*)\"/m', $result, $array);
				if( $matches[0] ){
					$this->pesan("[BING][".$i."] No results found for : ".$this->dork);
					$shc=1;
				}else{
					$this->pesan("[BING][".$i."] Bing w00t : ".count($array[1])." (".count($list).")  | ".count($inisite)." Domain");
					foreach($array[1] as $link){
						if(!preg_match("/live|msn|bing|microsoft/",$link)){
							if(!in_array($link,$list)){
								$sitene = "http://".$this->bingDomain($link);
								$list[] = $sitene."\r\n";
								$this->saves("engine/temp/search.txt",$sitene."\r\n");
							}
						}
					}
				}
			}
			$this->pesan("[BING] Total Bing w00t : ".count(array_unique($list)));
			foreach (array_unique($list) as $key => $valuex) {
				$inisite[] = $valuex;
				$this->saves("engine/temp/search-temp.txt",$valuex);
			}
		}
			foreach (array_unique($inisite) as $key => $hasils) {
				$this->saves("engine/result/search.txt",$hasils);
			}

	}
	
	function runEngine()
	{
		$this->bing();
	}

}
?>