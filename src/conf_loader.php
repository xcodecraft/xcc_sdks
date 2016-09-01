<?php

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

    static public function load($path)
    {
        $json = file_get_contents($path);
        return new XConfObj($json,$path) ;
    }

}
