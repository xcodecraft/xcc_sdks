<?php
namespace XCC ;

require_once(dirname(dirname(__file__)) . "/libs/beanstalk/beanstalkmq.php") ;
require_once(dirname(dirname(__file__)) . "/utls/utls.php") ;
require_once(dirname(__file__) ."/impl/hydra_bstalk.php")  ;

class HydraDTO
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
        if( is_object($data)) {
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
        if (is_object($data) )
        {
            $data = json_encode($data) ;
        }
        $this->data = base64_encode($data) ;
    }
    public function decode()
    {
        $odata = base64_decode($this->data);
        if ($this->cls != null)
        {
            $data = json_decode($odata) ;
            // settype($data,$this->cls) ;
        }
        if ($data != null )
            $this->data = $data ;
        else
            $this->data = $odata ;
    }
}


class Hydra
{
    static $logger = null ;
    static public function trigger($topic, $data, $tag=null,$delay=0,$ttl=60)
    {
        static $impl           = null ;
        if(empty(self::$logger))
        {
            static::$logger = new EmptyLogger() ;
        }
        $events = HydraConfLoader::getEvents();
        if (! in_array($topic,$events))
        {
            throw new \RuntimeException( "Hydra not support this event: $topic");
        }

        if(empty($impl)) $impl = new HydraBStalk($logger);
        $dto       = HydraDTO::create($topic,$data,$tag) ;
        $dto->encode();
        $json_data = json_encode($dto) ;
        $objid     = $impl->trigger(HydraDefine::TOPIC_EVENT, $json_data,  self::$logger,$delay, $ttl) ;

    }
}
