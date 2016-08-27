<?php
/**
 +---------------------------------------------------------------------------+
 | @author         : SHOR7CUT
 | @author URL     : http://www.facebook.com/bug7sec
 | @author Email   : zhero2day@gmail.com
 | @Greatz         : Tuban Cyber Team  , Bug7sec Team    , LinuxSec , IDCA
 |                   Defacer Tersakiti , SystemR0ot Team , Sec7or Team
 | @Spesial        : :( Bolehkah nama mu ku tulis disini ? 
 +---------------------------------------------------------------------------+
 +--------------- (             HELLO THE WORD            )------------------+
 +---------------------------------------------------------------------------+
 | LICENSE       : Creative Commons - Atribusi 4.0 Internasional
 | LICENSE URL   : https://creativecommons.org/licenses/by/4.0/legalcode.id
 | Copyright (c) 2016 BUG7SEC. All rights reserved.
 +---------------------------------------------------------------------------+
**/
include_once( 'class.engine.php' );
class ShcAction  extends ShcEngine
{       
        var $url;
        function setUrl($value){
                return $this->url = $value;
        }
        function pesan($value){
                $mesage = "[IDTookit] {$value}\r\n";
                if(!preg_match("/analyze/",$value)){
                        $this->report($mesage);
                }
                echo $mesage;
        }
        function report($data){
                $name = parse_url($this->url, PHP_URL_HOST).".txt";
                $name = "report/".$name;
                if(file_exists( $name )){
                        $myfile = fopen($name , "a+") or die("Unable to open file!");
                        fwrite($myfile, $data);
                        fclose($myfile);     
                }else{
                        $myfile = fopen($name , "a+") or die("Unable to open file!");
                        fwrite($myfile, "----------[ IDToolkit - Wordpress vulnerability scanner | Create Report : ".date("Y-m-d h:i:sa")."]----------\r\n\n");
                        fwrite($myfile, $data);
                        fclose($myfile); 
                }
        }
        function useragent(){
                $useragent      = explode("\r\n", file_get_contents("DB\user-agent.db"));
                $numbers        = range(0, count($useragent)-1);
                shuffle($useragent);
                return $useragent[$numbers[0]];
        }
        function GET()
        {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                curl_setopt($ch, CURLOPT_VERBOSE, false);
                $data = curl_exec($ch);
                return $data;
        }
        
        function checkServer(){
                $this->pesan("[INFO] CHECK ".$this->url);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->url);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                curl_setopt($ch, CURLOPT_VERBOSE, false);
                $data           = curl_exec($ch);    
                $header_size    = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header         = substr($data, 0, $header_size);
                $body           = substr($data, $header_size);
                $ex             = explode("\r\n", $header); 
                $xx             = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                for ($i=0; $i <4; $i++) {
                        $this->pesan("[INFO] ".$ex[$i]);
                }
                if($xx != $this->url){
                        $this->pesan("[INFO] Redirect ".$xx);
                        $this->setUrl($xx);
                        $this->checkServer();
                }
        }
        function checkCms(){
                $this->pesan("[analyze cms] Starting ... analyze version");
                $data = $this->GET();
                $re = "/\\/wp-(.*?)\\//"; 
                preg_match_all($re, $data, $matches);
                if($data == ""){
                    $this->checkCms();
                }
                if( $matches[0] ){
                        $this->pesan("[report cms] Found using wordpres");
                        $reversion = "/name=\"generator\" content=\"WordPress (.*?)\"/"; 
                        preg_match($reversion, $data, $matchesVer);
                        if($matchesVer[1]){
                                $this->pesan("[report cms] Wordpress Version ".$matchesVer[1]);
                                $this->pesan("[analyze] Check Vulnerability WordPress ".$matchesVer[1]);
                                $dbwp = explode("|", file_get_contents("DB\wp-version.db"));
                                $ver = str_replace(".", "", $matchesVer[1]);
                                foreach ($dbwp as $key => $value) {
                                        if($dbwp[$key] == $ver){
                                                $vervuln = true;
                                        }
                                }
                                if($vervuln){
                                        $this->pesan("[report cms] WordPress ".$matchesVer[1]." Vulnerability");
                                }else{
                                        $this->pesan("[report cms] WordPress ".$matchesVer[1]." Non-Vulnerability");
                                }
                        }else{
                                $this->pesan("[report cms] Wordpress Version is not detected"); 
                        }
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $this->url."wp-content/uploads/".date("Y"));
                        curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                        curl_setopt($ch, CURLOPT_VERBOSE, false);
                        $data           = curl_exec($ch);  
                        $httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if( $httpcode == 200){
                                $this->pesan("[report cms] Dirlisting  : Opened (wp-content/uploads/".date("Y").") ");
                        }else{
                                $this->pesan("[report cms] Dirlisting  : Close  (wp-content/uploads/".date("Y").")");
                        }
                }else{
                        $this->pesan("[analyze] This is not wordpress");
                        exit();
                }
        }
        function checkUser(){
                $this->pesan("[analyze user] Starting ... analyze user");
                $u = 1;
                $o = 100;
                $w = 0;
                for ($i=1; $i < $o  ; $i++) { 
                        $this->pesan("[analyze user] Start ".$u."/".$o." | Detected : ". ($w >= 1 ? $w : "-") );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $this->url."?author=".$i);
                        curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                        curl_setopt($ch, CURLOPT_VERBOSE, false);
                        $data           = curl_exec($ch);  
                        $xx             = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                        $httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $reuser         = "/\\/author\\/(.*?)\\//"; 
                        preg_match($reuser, $data, $matches);
                        if($httpcode == 200){
                                $user[] = $matches[1];
                                $w++;
                        }
                        $u++;
                }
                if($user){
                        foreach ($user as $key => $userName) {
                                $this->pesan("[report user] ".$userName);
                        }
                }else{
                                $this->pesan("[report user] --- Not Found ---");
                }
        }
        function checkTheme(){
                $this->pesan("[analyze themes] Starting ... analyze themes");
                $theme      = explode("|", file_get_contents("DB\wp-theme.db"));
                $xx = 1;
                $ss = 0;
                foreach ($theme as $key => $value) {
                        if(!empty($value)){
                                $this->pesan("[analyze themes] Start ".$xx."/".count($theme)." | Detected : ". ($ss >= 1 ? $ss : "-") );
                                $url = $this->url."wp-content/themes/".$value;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                                curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                                curl_setopt($ch, CURLOPT_VERBOSE, false);
                                $data = curl_exec($ch);
                                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                if($httpcode == 200){
                                        $themesVuln[] = $value."\r\n";
                                        $ss++;
                                }
                                $xx++;
                        }
               }
               if($themesVuln){
                        foreach ($themesVuln as $key => $themeName) {
                                $this->pesan("[report theme] ".$themeName);
                        }
                }else{
                                $this->pesan("[report theme] --- Not Found ---");
                }
        }
        function checkPlugin(){
                $this->pesan("[analyze Plugin] Starting ... analyze Plugin");
                $plugin      = explode("|", file_get_contents("DB\wp-plugin.db"));
                $xx = 1;
                $ss = 0;
                foreach ($plugin as $key => $value) {
                        $this->pesan("[analyze Plugin] Start ".$xx."/".count($plugin)." | Detected : ". ($ss >= 1 ? $w : "-") );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $this->url."wp-content/plugins/".$value);
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                        curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                        curl_setopt($ch, CURLOPT_VERBOSE, false);
                        $data = curl_exec($ch);
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if($httpcode == 200){
                                $plugins[] = $value."\r\n";
                                $ss++;
                        }
                        $xx++;
               }   
               if($plugins){
                        foreach ($plugins as $key => $pluginsName) {
                                $this->pesan("[report plugin] ".$pluginsName);
                        }
                }else{
                                $this->pesan("[report plugin] --- Not Found ---");
                }   
        }
        function checkShell(){
                $this->pesan("[analyze Shell] Starting ... analyze Shell");
                $dbshell    = explode("|", file_get_contents("DB\wp-shell.db"));
                $bulan = array('01','02','03','04','05','06','07','08','09','10','11','12');
                for ($i=2010; $i <date("Y")+1; $i++) { 
                        foreach ($bulan as $key => $buln) {
                                $url = $this->url."wp-content/uploads/".$i."/".$buln;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                                curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                                curl_setopt($ch, CURLOPT_VERBOSE, false);
                                $data = curl_exec($ch);
                                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                if($httpcode == 200){
                                      $found[] = $url;
                                }
                                if($httpcode == 403){
                                      $found[] = $url;
                                }
                        }
                }
                foreach ($found as $key => $UrlS) {
                        foreach ($dbshell as $key => $ShellName) {
                                $shcURL = $UrlS."/".$ShellName;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $shcURL);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent());
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/'."cuk.txt");
                                curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/'."cuk.txt");
                                curl_setopt($ch, CURLOPT_VERBOSE, false);
                                $data = curl_exec($ch);
                                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                if($httpcode == 200){
                                        $shell[] = $shcURL;
                                }

                        }
                }
                if($shell){
                        foreach ($shell as $key => $shellName) {
                                $this->pesan("[report shell] ".$shellName);
                        }
                }else{
                                $this->pesan("[report shell] --- Not Found ---");
                } 
        }
        function runIDT(){
                $name = parse_url($this->url, PHP_URL_HOST).".txt";
                $name = "report/".$name;
                if( file_exists($name) ){
                        unlink($name);
                }
                $this->checkServer();
                $this->checkCms();
                $this->checkUser();
                $this->checkTheme();
                $this->checkPlugin();
                $this->checkShell();
                $this->pesan("-- :END:SCAN --");
        }
}