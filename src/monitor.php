#!/usr/local/php/bin/php
<?php
namespace XCC ;
require_once(dirname(__file__) . "/xcc_sdk.php") ;

class XSdkCheck
{
    static public function check()
    {
        XSdkEnv::init();
        $confObj = XConfLoader::load(XConfLoader::ENV) ;
        assert(!empty($confObj->xpath("/env/xuuid"))) ;
        echo "\n...... check ok :) \n" ;

    }


}
XSdkCheck::check();
