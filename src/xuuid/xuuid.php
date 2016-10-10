<?php
namespace XCC ;
class Xuuid
{
    static public function id()
    {
        static $ins  = null ;
        if ($ins == null)
        {

            $confObj = XConfLoader::load(XConfLoader::ENV) ;
            $confs   = $confObj->xpath("/env/xuuid") ;
            $ins     = new \Memcache();
            foreach($confs as $conf)
            {
                $ins->addServer($conf['host'],$conf['port']);
            }

        }
        return  $ins->get("uuid") ;
    }
}
