<?php

namespace XCC ;

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
    public function error($msg,$tag="") {
        echo "error: $msg \n" ;
    }

    public function info($msg,$tag="") {
        echo "info: $msg \n" ;
    }
    public function debug($msg,$tag="") {
        echo "debug: $msg \n" ;
    }
}

class EmptyLogger
{
    public function __call($name,$params) {}
}
