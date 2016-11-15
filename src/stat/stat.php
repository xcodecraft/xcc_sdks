<?php
namespace XCC ;
interface IStat
{
    public function stat($name,$data="");

}
class EmptyStat implements IStat
{
    public function stat($name,$data="") {}
}

