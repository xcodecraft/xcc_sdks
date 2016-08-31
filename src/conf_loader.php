<?php

class XConfObj
{

    private $data ;
    public function __construct($json)
    {
        $this->data =  json_decode($json,true) ;

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
            $data = $this->getNode($data,$key) ;
        }
        return $data;
    }
    private function getNode($data,$key)
    {
        return $data[$key] ;
    }
}
class XConfLoader
{

    static public function load($path)
    {
        $json = file_get_contents($path);
        return new XConfObj($json) ;
    }

}
