<?php
namespace XCC ;
class  SentryStat implements IStat
{
    public function __construct()
    {
        $confObj      = XConfLoader::load(XConfLoader::ENV) ;
        $dsn          = $confObj->xpath("/stat/sentry_dsn") ;
        $this->client = new \Raven_Client($dsn);
    }
    public function stat($name,$data="")
    {
        $this->client->captureMessage($name,array($data),array("level" => 'info'));
    }
}
