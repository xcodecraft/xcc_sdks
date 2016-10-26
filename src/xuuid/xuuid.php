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
        static $uuid = 0 ;
        if ($uuid == 0 )
        {
            if ($ins == null)
            {

                $confObj = XConfLoader::load(XConfLoader::ENV) ;
                $confs   = $confObj->xpath("/xuuid") ;
                $ins     = new \Memcache();
                foreach($confs as $conf)
                {
                    $ins->addServer($conf['host'],$conf['port']);
                }

            }
            $uuid = (int)( $ins->get("uuid")) ;
        }
        $uuid ++ ;
        return $uuid ;
    }
}
