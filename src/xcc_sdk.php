<?php
namespace XCC ;
require_once("conf_loader.php") ;
class XSdkEnv
{
    static public function init()
    {
        XConfLoader::regist(XConfLoader::ENV,"/data/x/etc/env_conf/run_env/env.json");
        XCCSetting::reg_stat(function(){ return new SentryStat() ; }) ;
    }

}

class XCCSetting
{
    static $settings = array() ;
    static $loads    = array() ;
    public static function __callStatic($name,$args)
    {
        if( strlen($name) < 5 )
        {
            throw new LogicException("HydraSetting::$name is not allowed") ;
        }
        $op   = substr($name,0,4) ;
        $name = substr($name,4)  ;

        if ($op  == "get_")
        {
            if  (! isset(static::$settings[$name] ) && isset(static::$loads[$name]) )
            {
                $load = static::$loads[$name] ;
                $value = $load();
                return static::$settings[$name]  = $value ;
            }
            return static::$settings[$name] ;
        }
        if ($op == "set_")
        {
            return static::$settings[$name]  = $args[0];
        }
        if ($op == "reg_")
        {
            return static::$loads[$name]  = $args[0];
        }

    }

}
