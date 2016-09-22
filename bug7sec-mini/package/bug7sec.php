<?php
error_reporting(0);
require_once("package/required.php");
if($command[input][0] && $command[dork]  && $command[input][1] == "--full-patch"){
	Search::runSearch($command[dork],true);
}
if($command[input][0] && $command[dork]  && $command[input][1] == "--non-patch"){
	Search::runSearch($command[dork],false);
}
if( $command[input][1] == "--help" ){
	Action::help();
}    
                                     
if( $command[input][1] == "--update" ){
	Action::Update($version);
}
?>