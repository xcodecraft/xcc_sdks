<?php
class QConfDict
{

    public static function create_namespace($namespace )
    {
        if( QConfig::Exists($namespace) ) return true ;
        $parent_ns = dirname($namespace);
        if ($parent_ns != '.')
        {
            self::create_namespace($parent_ns);
        }
        Qconf::createConf($namespace,'');
        return true ;

    }
    public static function set($namespace ,$key, $val)
    {
        if( strpos($key,'/') != 0)
            throw new LogicException("[$key] include '/' ");
        if(!is_string($val))
            throw new LogicException("PlatoDict set value is not string");

        self::create_namespace($namespace);
        $full_key = "$namespace/$key";
        if (! QConfig::Exists($full_key))
            Qconf::createConf($full_key,'');
        Qconf::setConf($full_key,$val);

    }
    public static function  get($namespace,$key)
    {
        if( strpos($key,'/') != 0)
            throw new LogicException("[$key] include '/' ");
        $full_key = "$namespace/$key";
        return Qconf::getConf($full_key);
    }
}
class PlatoDict
{
    public static function set($namespace,$key, $val)
    {
        QConfDict::set("plato/$namespace" ,$key,$val);
//        XLogKit::logger("qconf")->debug("dict set ns: $namespace , key: $key, val: $val");
    }
    public static function  get($namespace,$key)
    {
        $val = QConfDict::get("plato/$namespace" ,$key);
//        XLogKit::logger("qconf")->debug("dict get ns: $namespace , key: $key, val: $val");
        return $val ;

    }
}

