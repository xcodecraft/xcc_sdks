<?php
namespace XCC ;
require_once("xuuid.php");
class XuuidIDGer  implements \XIDGenerator
{
    public function createID($idname='other')
    {
        return Xuuid::id();
    }
}
