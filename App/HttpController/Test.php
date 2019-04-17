<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/3/22
 * Time: 8:14 PM
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;

class Test extends Controller
{
     function index()
     {
         // TODO: Implement index() method.
         $data = [
             'name' => 'blank',
             'age' => 25
         ];
         $valitor = new \EasySwoole\Validate\Validate();
         $valitor->addColumn('name', '名字不为空')->required('名字不为空')->lengthMin(10, '最小长度不小于10位');
         $bool = $valitor->validate($data);
         var_dump($valitor->getError()->getErrorRuleMsg() ?: $valitor->getError()->getColumnErrorMsg());

     }
}