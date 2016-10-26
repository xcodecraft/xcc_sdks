#!/usr/local/php/bin/php
<?php
namespace XCC ;
require_once(dirname(__file__) . "/xcc_sdk.php") ;
require_once(dirname(__file__) . "/xuuid/xuuid.php") ;

class XSdkCheck
{
    static public function check()
    {
        XSdkEnv::init();
        $confObj = XConfLoader::load(XConfLoader::ENV) ;
        assert(!empty($confObj->xpath("/xuuid"))) ;
        echo "\n...... check ok :) \n" ;


        for($i =0 ; $i < 10 ; $i++)
        {
            $uid = Xuuid::id() ;
            echo "$uid\n"  ;
        }
    }
}
XSdkCheck::check();
