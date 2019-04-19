<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/19
 * Time: 2:22 PM
 */

namespace App\Utils;


class Date
{
    /**
     * 默认时间
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return false|string
     */
    static function defaultDate(){
        return date('Y-m-d H:i:s');
    }
}