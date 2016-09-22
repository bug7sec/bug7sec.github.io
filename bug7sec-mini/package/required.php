<?php
require_once("package/action.package.php");
require_once("package/search.package.php");
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
$command = arguments($argv);
mkdir("report");
mkdir("report/temp");
unlink("report/temp/result.txt");

$version = "1.0.1";
Action::cover($version);

?>