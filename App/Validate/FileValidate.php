<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/19
 * Time: 3:56 PM
 */

namespace App\Validate;


use EasySwoole\Spl\SplArray;
use EasySwoole\Validate\Validate;

class FileValidate extends BaseValidate
{
    /**
     * @author: zzhpeng
     * Date: 2019/4/19
     * @param $data
     *
     * @return string
     */
    public function qadd($data){
        // TODO: Implement index() method.
        $valitor = new Validate();
        $valitor->addColumn('domain', 'domain不能为空')->required('domain不能为空');
        $valitor->addColumn('fkey', 'fkey不能为空')->required('fkey不能为空');
        $valitor->addColumn('fname', 'fname不能为空')->required('fname不能为空');
        $valitor->addColumn('desc', 'desc不能为空')->required('desc不能为空');
        $valitor->addColumn('uid', 'uid不能为空')->required('uid不能为空');
        $bool = $valitor->validate($data);
        return $this->returnResult($bool,$valitor);
    }

    /**
     * @author: zzhpeng
     * Date: 2019/4/19
     * @param $data
     *
     * @return string
     */
    public function delete($data){
        // TODO: Implement index() method.
        $valitor = new Validate();
        $valitor->addColumn('id', 'id')->required('id不能为空');
        $bool = $valitor->validate($data);
        return $this->returnResult($bool,$valitor);
    }
}