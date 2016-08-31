<?php
/** @defgroup plato_sdk
 *
 */
require_once (dirname(__FILE__) . "/plato_impl.php");
require_once(dirname(dirname(__file__)) ."/conf/sdks_conf.php");
/**
 * @ingroup plato_sdk
 * @brief  PlatoClient
 */
class PlatoClient
{
    static public function conf($domain, $logger, $caller, $server = null,$port=8086)
    {
        $conf          = new XHttpConf();
        $conf->conf($domain, $logger);
        $conf->port    = $port ;
        $conf->timeout = 5;
        $conf->server  = $server;
        $conf->caller  = $caller;
        return $conf;
    }
    static public function localSvc($domain, $caller,$port=8086)
    {
        $logger     = XLogKit::logger("plato");
        $server     = "127.0.0.1";
        $conf       = self::conf($domain, $logger, $caller, $server);
        $conf->port = $port ;
        $wconf      = clone $conf ;
        $wconf->domain = "w.$domain" ;
        return new PlatoClient($conf,$wconf);
    }
    /**
     * @brief  获得标准Plato服务,不用考虑所在IDC和环境;
     *
     * @param $caller
     *
     * @return
     */
    static public function stdSvc($caller, $logger = null)
    {

        if (empty($logger))
        {
            if (function_exists("pylon_dict_find") ){
                $logger = XLogKit::logger("plato");
            }
            else {
                throw new LogicException("PlatoClient 调用没有传入的logger对象!");
            }
        }
        $pconf         = new PlatoConf;
        $conf          = self::conf($pconf->svc, $logger, $caller, $server);
        $conf->proxy   = $pconf->proxy ;
        $conf->port    = $pconf->port ;
        $wconf         = clone $conf ;
        $wconf->domain = $pconf->wsvc ;
        return new PlatoClient($conf,$wconf);
    }
    public function __construct($conf,$wconf)
    {
        $this->rSvc = new PlatoClient_1_0($conf);
        $this->wSvc = new PlatoWriter($wconf);
    }
    public function getConf($scenes, $args = null)
    {
        $svc = $this->rSvc ;
        return $svc->getConf($scenes, $args);
    }
    /**
     * @brief
     *
     * @param $scenes   例如:"/plato_auth/nav,/plato_auth/rigger"
     * @param $env      环境名如 "online" ,"beta"
     * @param $args     保留参数
     * REST uri: "/env/$env/scene/$scenes$args" ;
     * REST method: "GET" ;
     *
     * @return
     */
    public function getEnvConf($scenes, $env = 'online', $args = null)
    {
        $svc = $this->rSvc ;
        return $svc->getEnvConf($scenes, $env, $args);
    }
    public function envConfREST($scenes, $env = 'online', $args = null)
    {
        $svc = $this->rSvc ;
        $url = $svc->confURI($scenes, $env, $args);
        $conf = $svc->confobj;
        return "http://{$conf->domain}:{$conf->port}$url";
    }
    /**
     * @brief  写接口
     *
     * @param $scene
     * @param $json_conf
     *
     * @return
     */
    public function upConf($scene, $json_conf)
    {
        $svc = $this->wSvc ;
        return $svc->upConf($scene, $json_conf);
    }

    static public function std_scene_path($scene)
    {
        $scene = str_replace(">", "/", $scene);
        return $scene;
    }
}
