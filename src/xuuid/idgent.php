<?php
namespace XCC ;
require_once("xuuid.php");
class XuuidIDGer  implements \XIDGenerator
{
    public function createID($idname='other')
    {
        $xid    = Xuuid::id();
        file_put_contents("/tmp/xuuid",$xid."\n",FILE_APPEND);
        return $xid;
    }
}
