<?php
require_once(dirname(dirname(__file__)) ."/conf/sdks_conf.php");

class OmitSDK {
    static public function conf($domain, $port=8086, $proxy=null )
    {
        $conf          = new XHttpConf();
        $logger = XLogKit::logger('OmitSdk');
        $conf->conf($domain, $logger);
        $conf->proxy   = $proxy;
        $conf->port    = $port;
        $conf->timeout = 5;
        return $conf;
    }

    public function __construct($conf=null)
    {
        if(empty($conf)) {
            $omitConf = new OmitConf();
            $conf = static::conf($omitConf->svc, $omitConf->port);
        }
        $this->svc = new OmitClient($conf);
    }

    public function omit($app, $keys, $attr) {
       return $this->svc->omit($app, $keys, $attr); 
    }


    public function incr($name, $value = 1) {
       return $this->svc->incr($name, $value); 
    }

    public function get($name) {
        return $this->svc->get($name);
    }

}
