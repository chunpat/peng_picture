<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/19
 * Time: 10:41 AM
 */

namespace App\Validate;


use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\Validate;

class BaseValidate extends Validate
{
    protected function returnResult($bool,Validate $valitor){
        return $bool ? '': ( $valitor->getError()->getErrorRuleMsg() ?: $valitor->getError()->getColumnErrorMsg() );
    }
}