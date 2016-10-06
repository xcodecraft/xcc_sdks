

# 使用接口
```php
class QueueDTO
{
    public $name ;
    public $data ;
    public $cls  ;
    public $tag  ;
    public $happen ;
}
class Queue
{
    static public function push($topic, $data,$tag="" );
    static public function fetch($topic, $timeout=5 );
}

interface QueueConsume
{
    public function consume(QueueDTO $dto)  ;
    public function needStop($job) ;

}


class QueueSvc
{
    static public function serving($topic,$consumer,$logger,$timeout = 5  );

}


```

# Push 数据;
```php

$data  = [ "key" => "name" , "val" => "queue"] ;
$topic = "xcc_queue_test" ;
Queue::push($topic,$data) ;

```




# Consume 数据
```php

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
        return $i >= 1000 ;
    }

}


public function testQueueConsumer()
{
    QueueSvc::serving($topic,new MyConsume, new XCC\EchoLogger() ) ;
}


```


# Fetch 数据
```php

list($flag,$dto)  = Queue::fetch("xcc_queue_test") ;
if($flag !== null)
{
    //do some thing
    $flag(true);
}

```
