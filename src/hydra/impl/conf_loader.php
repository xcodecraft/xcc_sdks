<?php

class HydraConfLoader
{
    static $conf_file = "/data/x/tools/env_setting/conf/xcc/sdks_conf.php" ;

    static public function hash($str) {
        //hash(i) = hash(i-1) * 33 + str[i]
        $hash = 0;
        $s    = md5($str);
        $seed = 5;
        $len  = 32;
        for ($i = 0; $i < $len; $i++) {
         // (hash << 5) + hash 相当于 hash * 33
         $hash = ($hash << $seed) + $hash + ord($s{$i});
         }
        return $hash & 0x7FFFFFFF;
    }

    static public function  getSubConf($topic)
    {
        // $qConfs = HydraConf::subConfs();
        $hVal    = self::hash($topic) ;
        $confObj = XConfLoader::load(static::$conf_file ) ;
        $qConfs  = $confObj->xpath("/hydra/collectos") ;
        $start   = 0 ;

        foreach ($qConfs as $key=>$conf)
        {
            if ($hVal > $start  &&  $hVal < $key)
            {
                return $conf;
            }
            $start = $key ;
        }
        return null ;
    }
    static public function getCollectors()
    {
        $confObj  = XConfLoader::load(static::$conf_file ) ;
        return $confObj->xpath("/hydra/collectos") ;
    }

}
