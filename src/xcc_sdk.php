<?php
namespace XCC ;
require_once("conf_loader.php") ;
class XSdkEnv
{
    static public function init()
    {
        XConfLoader::regist(XConfLoader::ENV,"/data/x/etc/env_conf/conf/xcc/sdks_conf.json");
    }

}

class Monitor
{

}

