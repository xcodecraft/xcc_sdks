<?php


class XCCSDKTest  extends PHPUnit_Framework_TestCase
{

    public function testConfloader()
    {

        $dataPath  = XSetting::ensureEnv("PRJ_ROOT") ;
        $dataPath .= "/test/data" ;
        $confObj   = XConfLoader::load($dataPath . "/conf.json") ;
        $data      = $confObj->xpath("/hydra/collectors") ;
        $this->assertTrue(!empty($data)) ;

        $data      = $confObj->xpath("/hydra/subscibes") ;
        $this->assertTrue(!empty($data)) ;
    }

}
