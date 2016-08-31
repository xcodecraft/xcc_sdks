<?php

class PlatoWriter extends XHttpCaller
{

    public function upConf($scene, $json_conf)
    {
        $scene = trim($scene, "/");
        $pos   = strpos($scene, "/");
        if ($pos === FALSE) {
            $prj   = $scene;
            $scene = "/" ;
        }
        else {
            $prj   = substr($scene, 0, $pos + 1);
            $scene = substr($scene, $pos + 1);
        }
        $scene             = PlatoClient_1_0::encode_scene_path($scene);
        $data['json_conf'] = $json_conf;
        $result            = $this->post("/project/$prj/scene/$scene/update", $data);
        return $result;
    }
}
class PlatoClient_1_0 extends XHttpCaller
{
    /**
     * @brief  生成常用conf 对象;
     * port   : 8360  ,timeout: 5 s  , server : 127.0.0.1
     *
     *
     * @param $domain  conf server 的地址
     * @param $logger
     * @param $caller
     * @param $server
     *
     * @return
     */

    public function __construct($conf)
    {
        XHttpCaller::__construct($conf);
        $this->confobj = $conf ;

    }
    static public function encode_scene_path($scene)
    {
        $scene = str_replace("/", ">", $scene);
        return $scene;
    }
    /**
     * @brief  获得配置信息
     *
     * @param $scenes  ex: /toolbar/sxd/wan,/toolbar/sxd/qipai
     * @param $args
     *
     * @return
     */

    public function getConf($scenes, $args = null)
    {
        return $this->getEnvConf($scenes, 'online', $args);
    }
    /**
     * @brief 获得环境下的配置
     *
     * @param $scenes
     * @param $env   环境  online , test ;  default online
     * @param $args
     *
     * @return
     */

    public function getEnvConf($scenes, $env = 'online', $args = null)
    {
        $result = $this->get($this->confURI($scenes, $env, $args));
        $data   = XRestResult::ok($result) ;
        return $data ;
    }

    public function confURI($scenes, $env = 'online', $args = null)
    {
        if (is_array($scenes)) {
            $scenes = implode(",", $scenes);
        }
        if (empty($scenes)) throw new LogicException(" scenes is empty");
        $scenes = self::encode_scene_path($scenes);
        if (!empty($args) )
        {
            $args = "?" . http_build_query($args);
        }
        $url = "/env/$env/scene/$scenes$args" ;
        return $url;
    }

    public function getDeepConf()
    {
        DBC::unImplement("考虑不支持！");

    }

}
