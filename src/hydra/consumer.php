<?php
namespace XCC ;
require_once(dirname(dirname(__file__)) . "/libs/beanstalk/beanstalkmq.php") ;
require_once(dirname(dirname(__file__)) . "/utls/utls.php") ;
require_once(dirname(__file__) ."/impl/hydra_bstalk.php")  ;

interface HydraConsume
{
    public function consume(HydraDTO $dto)  ;
    public function needStop($job) ;

}
class HydraSvc
{

    private $consumeTopic =  null ;
    private $consumeObj   =  null ;
    public function __construct()
    {
        $this->impl = new HydraBStalk();
    }
    public function serving($logger = null ,$timeout = 5  )
    {

        if(empty($logger)) $logger = new HydraEmptyLogger();

        $topic      = $this->consumeTopic ;
        $consumeObj = $this->consumeObj ;
        $tag        = "consume:@$topic" ;
        $host       = gethostname() ;
        XCCSetting::get_stat()->stat("HydraSvc:$topic-$host");
        $call = function ($data)use($consumeObj,$logger,$tag)
        {
            $logger->debug("job data:" . $data , $tag) ;
            $obj = HydraDTO::fromJson($data) ;
            if($obj == null)
            {
                if($logger) $logger->warn("bad HydraDTO",$tag) ;
                return  ;
            }
            $obj->decode();
           return $consumeObj->consume($obj);
        } ;

        return $this->impl->consume($topic, $call,  array($consumeObj,"needStop") ,$logger,$timeout);
    }

    public function subscribe($topic,$client,HydraConsume $consumeObj,$logger=null)
    {

        if(empty($logger)) $logger = new EmptyLogger();
        $cmd = new HydraCmd;
        $cmd->cmd           = "subscribe" ;
        $cmd->client        = $client ;
        $cmd->topic         = $topic ;
        $this->impl->cmd($cmd,$logger) ;
        $this->consumeTopic = "$topic-$client" ;
        $this->consumeObj   = $consumeObj ;
    }
    public function unSubscribe($topic,$client,$logger=null)
    {
        if(empty($logger)) $logger = new EmptyLogger();
        $cmd = new HydraCmd;
        $cmd->cmd    = "unsubscribe" ;
        $cmd->client = $client ;
        $cmd->topic  = $topic ;
        $this->impl->cmd($cmd,$logger) ;
        $this->consumeTopic =  null;
        $this->consumeObj   =  null;
    }
}
