<?
require_once("hydra.php") ;




class ConsumeDemo implements HydraConsume
{
    public function consume(HydraDTO $dto) 
    {
        echo "-----------------\n" ;
        echo $dto->name  ;
        echo " " ;
        echo $dto->data ;
        echo "\n" ;
        return true ;

    }
    public function needStop($job)
    {
        if(!$job) return true ;
        return false ;
    }
}



$logger   = new EmptyLogger();
$consumer = new HydraSvc();
$consumer->subscribe("echo","demo",new ConsumeDemo);
sleep(1) ;

Hydra::trigger("echo","pylon") ;
Hydra::trigger("echo","rigger") ;
$consumer->serving($logger,5);

$consumer->unsubscribe("echo","demo");
