<?php
namespace XCC ;
class XuuidServer
{
    public function __construct()
    {
        $this->ins = new \Memcache();
    }

    public function __destruct()
    {
        $this->ins->close();
        unset($this->ins);
    }

    public function __call($method,$args)
    {
        return call_user_func_array(array($this->ins,$method),$args);
    }
}

class Xuuid
{
    static public function cacheGet($key,$fun,$ttl)
    {
        if(apcu_exists($key)){
            return apcu_fetch($key);
        }
        $data =  call_user_func($fun) ;
        apcu_store($key,$data,$ttl);
        return $data;
    }
    static public function id()
    {
        static $ins  = null ;
        if ($ins == null)
        {

            $confObj = XConfLoader::load(XConfLoader::ENV) ;
            $confs   = $confObj->xpath("/xuuid") ;
            $ins     = new XuuidServer();
            foreach($confs as $conf)
            {
                $ins->addServer($conf['host'],$conf['port']);
            }

        }
        for($i = 0 ; $i< 10 ; $i++ )
        {
            $uuid =  $ins->get("uuid") ;
            if(!empty($uuid) )
            {
                return $uuid ;
            }
            else
            {
                if (class_exists('XLogKit'))
                {
                    \XLogKit::logger("main")->error("xuuid is empty!") ;

                }
            }
        }
        throw new \RuntimeException("没有正确生成对象ID $uuid") ;
    }
}
