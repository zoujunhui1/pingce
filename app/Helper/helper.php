<?php
namespace App\Helper;

//获取数据偏移
function getOffset($page,$limit)
{
    $offset = ceil(($page-1)*$limit);
    $offset = $offset < 0 ? 0 : $offset;
    return $offset;
}
