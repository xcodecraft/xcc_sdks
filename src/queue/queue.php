<?
namespace XCC ;

require_once(dirname(dirname(__file__)) . "/libs/beanstalk/beanstalkmq.php") ;
require_once(dirname(dirname(__file__)) . "/utls/utls.php") ;


class QueueDTO
{
    public $name ;
    public $data ;
    public $cls  ;
    public $tag  ;
    public $happen ;
    static public function create($name,$data,$tag)
    {
        $cls         = __class__ ;
        $dto         = new $cls ;
        $dto->happen = time();
        $dto->name   = $name ;
        $dto->tag    = $tag ;
        if(is_object($data)) {
            $dto->cls  = get_class($data) ;
        }
        $dto->data = $data;
        return $dto;

    }
    static public function fromJson($json)
    {

        $cls          = __class__ ;
        $dto          = new $cls ;
        $obj          = json_decode($json) ;
        if($obj  == null ) return null ;
        $dto->name    = $obj->name ;
        $dto->happen  = $obj->happen;
        $dto->tag     = $obj->tag ;
        $dto->cls     = $obj->cls ;
        $dto->data    = $obj->data ;
        return $dto ;
    }
    public function encode()
    {

        $data = $this->data ;
        if (!is_string($data) )
        {
            $data = json_encode($data) ;
        }
        $this->data = base64_encode($data) ;
    }
    public function decode()
    {
        $odata = base64_decode($this->data);
        $toArr = true ;
        if(!empty($this->cls))
        {
            $toArr = false ;
        }
        $data  = json_decode($odata,$toArr) ;
        if ($data != null )
        {
            $this->data = $data ;
        }
        else
        {
            $this->data = $odata ;
        }
    }
}

class Queue
{
    static $logger = null ;
    CONST RTY_TIME = 10; // 任务重试时间
    static public function push($topic, $data,$tag="" )
    {
            $dto       = QueueDTO::create($topic,$data,$tag) ;
            $dto->encode();
            $json_data = json_encode($dto) ;

            if(empty(static::$logger)) static::$logger = new EmptyLogger() ;
            static::$logger->debug($json_data) ;
            $ins   = static::rollIns($topic);
            $jobId = $ins->putInTube($topic, $json_data, $priority=1024, $delay=0, $ttl=60);
            static::$logger->debug("send $jobId @$topic") ;
            return $jobId ;

    }
    static public function fetch($topic, $timeout=5 )
    {
        if(empty(static::$logger)) static::$logger = new EmptyLogger() ;
        static::$logger->info("watch $topic","consume") ;
        $Q      = static::rollIns($topic);
        $job    = $Q->watch($topic)->ignore('default')->reserve($timeout);
        $result = false ;
        if( !$job )
        {
            static::$logger->debug("no job ", $topic );
            return null ;
        }
        $jid  = $job->getId();
        $data = $job->getData();
        static::$logger->debug("get job:$jid",$topic) ;
        static::$logger->debug($data,$topic) ;

        $obj = QueueDTO::fromJson($data) ;
        if($obj == null)
        {
            static::$logger->warn("bad QueueDTO",$tag) ;
            return  null ;
        }
        $obj->decode();
        $call = function ($result)use($Q,$job)
        {
            if( $result === true) {
                static::$logger->info("job[$jid] proc suc! ",$topic) ;
                $Q->delete($job);
            }
            else {
                $Q->release($job, 1024, Queue::RTY_TIME );
            }
        };
        return [$call,$obj] ;

    }

    static private function rollIns($topic)
    {
        static $index  = 0 ;
        $queues = self::insConnect($topic,$logger);
        $ins    = $queues[$index] ;
        $index ++ ;
        if($index >= count($queues) ) $index =0 ;
        return $ins ;

    }
    static private function insConnect($topic)
    {
        static $queues = array() ;
        if (isset($queues[$topic]))
        {
            return $queues[$topic] ;
        }
        $queues[$topic] = array() ;
        $confObj        = XConfLoader::load(XConfLoader::XCC) ;
        $qConfs         = $confObj->xpath("/queue/$topic") ;
        static::$logger->debug( "queue [$topic] use $conf" ) ;
        foreach( $qConfs as $conf)
        {
            list($host,$port) = explode(':',$conf) ;
            array_push($queues[$topic], new \Pheanstalk_Pheanstalk($host, $port, 60));
        }
        return $queues[$topic] ;

    }

}

interface QueueConsume
{
    public function consume(QueueDTO $dto)  ;
    public function needStop($job) ;

}

class QueueSvc
{


    static function consume($topic, $workFun, $stopFun, $logger  ,$timeout=5 )
    {
        Queue::$logger =$logger ;
        while(true)
        {
            if (is_callable($stopFun) && call_user_func($stopFun,$job) == true )  return ;
            list($flag,$data) = Queue::fetch($topic,$timeout)  ;
            try{
                $result = call_user_func($workFun, $data);
            }
            catch(Exception $e)
            {
                $logger->warn("job failed: " . $e->getMessage(),$tag);
            }
            $flag($result);
        }

    }


    static public function serving($topic,$consumer,$logger,$timeout = 5  )
    {

        return static::consume($topic,[$consumer,'consume'], [$consumer,'needStop'],$logger,$timeout) ;
    }

}

