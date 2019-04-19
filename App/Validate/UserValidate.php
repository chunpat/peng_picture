<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/19
 * Time: 10:38 AM
 */

namespace App\Validate;


use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\Validate;

class UserValidate extends BaseValidate
{
    public function add($data){
        // TODO: Implement index() method.
        $valitor = new Validate();
        $valitor->addColumn('account', '账号')->required('账号不为空')->func(function (SplArray $objeat){
            return preg_match("/^1[34578]\d{9}$/", $objeat->get('account'));
        }, '手机格式不对');
        $valitor->addColumn('password', '密码')->required('密码不为空')->lengthMin(6,'长度不能小于6位数');
        $valitor->addColumn('password', '密码')->required('密码不为空')->lengthMin(6,'长度不能小于6位数');
        $bool = $valitor->validate($data);

        return $this->returnResult($bool,$valitor);
    }
//
//    function is_mobile($str){
//        return preg_match("/^(((d{3}))|(d{3}-))?13d{9}$/", $str);
//    }
}