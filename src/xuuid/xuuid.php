<?php
namespace XCC ;
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
            $ins     = new \Memcache();
            foreach($confs as $conf)
            {
                $ins->addServer($conf['host'],$conf['port'],true);
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
