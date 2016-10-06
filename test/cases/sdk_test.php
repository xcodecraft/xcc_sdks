<?php

use XCC\XConfLoader ;
use XCC\Xuuid ;




class XCCSDKTest  extends PHPUnit_Framework_TestCase
{

    public function testConfloader()
    {

        $confObj   = XConfLoader::load("xcc") ;
        $data      = $confObj->xpath("/hydra/collectors") ;
        $this->assertTrue(!empty($data)) ;

        $data      = $confObj->xpath("/hydra/subscibes") ;
        $this->assertTrue(!empty($data)) ;


        $data      = $confObj->xpath("/kvstore") ;
        $this->assertTrue(!empty($data)) ;
    }
    public function testXuuid()
    {
        $uid = Xuuid::id() ;
    }
}
