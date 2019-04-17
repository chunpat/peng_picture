<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:15 PM
 */

namespace App\Enum;


use EasySwoole\Spl\SplEnum;

class Scope extends SplEnum
{
    const ROLE_ACCESS = 1;
    const ROLE_REFRESH = 2;
}