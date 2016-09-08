<?php

namespace xcc ;
use LogicException ;

class XConfObj
{

    private $data ;
    public function __construct($json,$from)
    {
        $this->from = $from ;
        $this->data =  json_decode($json,true) ;
        if($this->data == null)
        {
            throw new LogicException("bad json format! [{$this->from}]") ;
        }

    }
    //TODO : 简单实现,没有完整支持 xpath ;
    public function xpath($path)
    {
        $data = $this->data ;
        $arr = explode('/', trim($path)) ;
        while( true)
        {
            $key = array_shift($arr) ;
            if( $key === null )
            {
                break ;
            }
            if($key === "")
            {
                continue ;
            }
            $data = $this->getNode($data,$key,$path) ;
        }
        return $data;
    }
    private function getNode($data,$key,$path)
    {
        $found = $data[$key] ;
        if($found === null)
        {
            throw new LogicException("not found [$path : $key] conf in [{$this->from}]") ;
        }
        return $found ;
    }
}
class XConfLoader
{
    const XCC = "xcc" ;
    const ALI = "ali" ;

    static private $reg_paths = array() ;
    static public function regist($name,$path)
    {
        static::$reg_paths[$name] = $path ;
    }
    static public function registXCC($env_name)
    {
        static::regist(static::XCC,static::needEnv($env_name)) ;

    }
    static public function registALI($env_name)
    {
        static::regist(static::ALI,static::needEnv($env_name)) ;

    }

    static public function load($name)
    {
        if(!isset(static::$reg_paths[$name]))
        {
            throw new LogicException("not regist [$name] to XConfLoader ") ;
        }
        $path = static::$reg_paths[$name] ;
        $json = file_get_contents($path);
        return new XConfObj($json,$path) ;
    }
    static public function needEnv($name)
    {
        $val = $_SERVER[$name] ;
        if($val == null)
        {
            throw new LogicException("not found $env_name env val!") ;
        }
        return $val ;

    }

}
