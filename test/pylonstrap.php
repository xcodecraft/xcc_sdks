<?php

use XCC\XConfLoader ;

$dataPath  = XSetting::ensureEnv("PRJ_ROOT") ;
$dataPath .= "/test/data/conf.json" ;
XConfLoader::regist("env",$dataPath) ;
// XConfLoader::registEnv($dataPath) ;
