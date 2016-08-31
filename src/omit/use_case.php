<?php

require_once(dirname(__file__).'/omit_sdk.php');
require_once(dirname(__file__).'/sdks_conf.php');

$conf = OmitSDK::conf('omit.xcodecraft.cn', 8086);
$conf = new OmitConf();
// $conf 可以不传。
$omitSvc = new OmitSDK($conf);

$app  = 'ayi_svc';  //  命名空间， 必填
$key = array(   // 查重数据， 数组，必填
    'phone' => 13812345678,
    'custid' => 12580,
    );

$attr = array(   // 查重特性
    'cnt' => 1,  // 可以允许多次重复
    'ttl' => 60, // 有效期， 单位秒。  超过这个时间之后，相同的数据不再认为是重复数据。
    );


$r = $omitSvc->omit($app, $key, $attr);

