<?php

use XCC\Queue ;
use XCC\QueueConsume ;
use XCC\QueueSvc ;
use XCC\QueueDTO ;


class MyConsume implements QueueConsume
{
    public function consume(QueueDTO $dto)
    {
        var_dump($dto->data) ;
    }
    public function needStop($job)
    {
        static $i = 0 ;
        $i++ ;
        return $i >= 2 ;
    }

}

class QueueTest  extends PHPUnit_Framework_TestCase
{


    public function testQueue()
    {
        Queue::$logger = new XCC\EchoLogger();
        $data = [ "key" => "name" , "val" => "queue"] ;
        Queue::push("xcc_queue_test",$data) ;
        list($flag,$dto)  = Queue::fetch("xcc_queue_test") ;
        $flag(true);
    }

    public function testQueueConsumer()
    {
        $data  = [ "key" => "name" , "val" => "queue"] ;
        $topic = "xcc_queue_test" ;
        Queue::push($topic,$data) ;

        QueueSvc::serving($topic,new MyConsume, new XCC\EchoLogger() ) ;

    }

}
