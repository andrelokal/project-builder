<?php
session_start();

ini_set('short_open_tag','1');
date_default_timezone_set('America/Sao_Paulo');

define("PATH_ROOT", @($_SERVER['DOCUMENT_ROOT']."/"));
define("PATH", @($_SERVER['HTTP_REFERER']."/"));
define("HOST", @($_SERVER['SERVER_ADDR']));

$conf = parse_ini_file(PATH_ROOT."util/config.ini");

define("TITLE", $conf['TITLE']);

if($conf['TIME_ZONE'])
	date_default_timezone_set($conf['TIME_ZONE']);

//autoload das classes
function __autoload($classe){
	if(!@include_once PATH_ROOT."model/".$classe.".php");
		if(!@include_once PATH_ROOT."util/".$classe.".php");
			if(!@include_once PATH_ROOT."control/".$classe.".php");
				if(!@include_once PATH_ROOT."engineer/".$classe.".php");
}

?>