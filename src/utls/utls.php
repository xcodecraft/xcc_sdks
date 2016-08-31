<?php
class NullProxy
{
    public function __construct($origin)
    {
        $this->origin = $origin ;
    }
    public function __call($name , array $arguments)
    {
        if ($this->origin != null) {
            return  call_user_func_array(array($this->origin,$name),$arguments)  ;
        }
        return  null ;
    }
}

class EchoLogger
{
    public function error($msg) {
        echo "error: $msg \n" ;
    }

    public function info($msg) {
        echo "info: $msg \n" ;
    }
    public function debug($msg) {
        echo "debug: $msg \n" ;
    }
}
