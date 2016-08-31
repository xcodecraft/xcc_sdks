#

##
``` php

$plato = PlatoClient::stdsvc("example");
$data  = $plato->getEnvConf("self_monitor") ;

```
示例
``` php
       require_once("/data/x/sdks/platform_sdks/plato/plato.php")
       $plato    = PlatoClient::stdSvc("ayi_coupon");
       $data     = $plato->getEnvConf($dto->category);
       $category = PlatoClient::std_scene_path($dto->category) ;
       if(!isset($data["$category"]))
           throw new XBizException("配置在Plato 没有定义! $category") ;

```
