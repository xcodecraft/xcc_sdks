<?php
include  dirname(dirname(__file__)) . "/vendor/xcodecraft/pylon.autoload/src/autoload.php" ;
use XCC\XConfLoader ;
$dataPath  = getenv("PRJ_ROOT") ;
$dataPath .= "/test/data/conf.json" ;
XConfLoader::regist("env",$dataPath) ;
