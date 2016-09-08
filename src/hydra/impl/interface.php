<?php

namespace XCC ;

class HydraCmd
{
    public $cmd ;
    public $topic ;
    public $client ;
}

interface  ICollector
{

    public function trigger($topic, $data, $logger,$delay=0,$ttl=60)  ;

}

interface IConsumer
{

    public function cmd(HydraCmd $cmd,$logger)  ;
    public function consume($topic, $workFun, $stopFun, $logger  ,$timeout=5 )  ;

}

class HydraDefine
{
    const  TOPIC_CMD    = "__CMD__"   ;
    const  TOPIC_EVENT  = "__EVENT__" ;
    const  TIMEOUT      = 500 ;
}
class HydraConf
{
    static public $collectors = [ "10.170.191.164:11300" ] ;


    static public function subConfs()
    {
        static $qConfs = null ;
        if(empty($qConfs))
        {
            $qConfs = array();
            $qConfs[0x8FFFFFFF] = "10.170.191.164:11301" ;
            $qConfs[0xFFFFFFFF] = "10.170.191.164:11302" ;
        }
        return $qConfs ;
    }
}
