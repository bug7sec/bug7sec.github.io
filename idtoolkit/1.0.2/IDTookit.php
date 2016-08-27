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
 [PENTING] Report Issue : https://github.com/bug7sec/bug7sec.github.io/issues
**/
error_reporting(0);
set_time_limit();
include_once( 'class.config.php' );
include_once( 'package/class.action.php' );
include_once( 'package/class.engine.php' );
$action  	= new ShcAction;
$engine  	= new ShcEngine;
$command 	= arguments($argv);

if( $command['input']['1'] == "--help" ){
	$config->help();
}
if( $command['input']['1'] == "--update" ){
	$config->checkUpdate();
}
if( $command['input']['1'] == "--check-db" ){
	ShcConfig::checkEngine();
}
if( $command['input']['1'] == "--clear-engine" ){
	ShcConfig::clearEngine();
}
if( $command['url'] && $command['input']['1'] == "--test"){
	$action->setUrl($command['url']);
	$action->runIDT();
}
if( $command['dork'] && $command['input']['1'] == "--no-filter" ){
	$engine->search($command['dork']);
	$engine->runEngine();
}
if( $command['list'] && $command['input']['1'] == "--test" ){
	$action->setList($command['list']);
	$action->runBylist();
}
if( $command['input']['1'] == "--from-db" && $command['input']['2'] == "--filter-wp"){
	$action->filterbyDB();
}
if( $command['list'] && $command['input']['1'] == "--filter-wp"){
	$action->setList($command['list']);
	$action->filterbyList();
}
if( $command['dork'] && $command['input']['1'] == "--filter"){
	$engine->search($command['dork']);
	$engine->runEngine();
	$action->filterbyDB();
}
if( $command['dork'] && $command['input']['1'] == "--full" ){
	$engine->search($command['dork']);
	$engine->runEngine();
	$action->filterbyDB();
	$action->testbyDB();
}
if( $command['input']['1'] == "--from-db" && $command['input']['2'] == "--test"){
	$action->testbyDB();
}

?>