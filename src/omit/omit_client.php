<?php

class OmitClient {
    public function __construct($conf) {
        $this->curl = new XHttpCaller($conf);
    }

    public function omit($app, $key, $attr=null) {
        if(empty($attr)) {
            $attr = array('cnt'=>1, 'ttl'=> 300);
        }
        $r = $this->curl->post('/v1/omit', array('app'=>$app, 'key'=>$key, 'attr'=>$attr));
        if($r) {
            return intval($r->body());
        }
        return false;
    }

    public function incr($name, $value) {
        $r = $this->curl->post('/v1/counter/incr', array('name'=>$name, 'value'=>$value));
        if($r)
            return intval($r->body());
        return false;
    }

    public function get($name) {
        $r = $this->curl->get('/v1/counter/get?name='.$name);
        return intval($r->body());
    }
}
