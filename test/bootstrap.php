<?php
include  dirname(dirname(__file__)) . "/vendor/xcodecraft/pylon.autoload/src/autoload.php" ;
use XCC\XConfLoader ;
use XCC\XSdkEnv ;
XSdkEnv::init();
XCC\XCCSetting::reg_stat(function(){ return new XCC\EmptyStat() ; }) ;
$dataPath  = getenv("PRJ_ROOT") ;
$dataPath .= "/test/data/conf.json" ;
XConfLoader::regist("env",$dataPath) ;
