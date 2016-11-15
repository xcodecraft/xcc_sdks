<?php

use XCC\Xuuid ;

class XuuidTest extends PHPUnit_Framework_TestCase
{
    public function testXuuid()
    {
        for($i =0 ; $i < 100 ; $i++)
        {
            $uid = Xuuid::id() ;
            echo "$uid," ;
        }
    }
}
