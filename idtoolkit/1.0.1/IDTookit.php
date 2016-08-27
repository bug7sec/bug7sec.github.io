<?php
include_once( 'package/class.config.php' );
include_once( 'package/class.action.php' );
$action  	= new ShcAction;
$engine  	= new ShcEngine;
$command  	= new ShcConfig;
$command 	= arguments($argv);
if( $command['input']['1'] == "--help" ){
	echo "php ".$argv[0]." --url=http://example.com --test";
}
if( $command['url'] && $command['input']['1'] == "--test"){
	$action->setUrl($command['url']);
	$action->runIDT();
}
?>