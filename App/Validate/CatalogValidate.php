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

class CatalogValidate extends BaseValidate
{
    /**
     * @author: zzhpeng
     * Date: 2019/4/19
     * @param $data
     *
     * @return string
     */
    public function add($data){
        // TODO: Implement index() method.
        $valitor = new Validate();
        $valitor->addColumn('name', '目录名')->required('目录名不为空')->betweenLen(2,10,'目录名长度为2到10');
        $valitor->addColumn('parent_id', '父类id')->required('父类id不为空')->integer();
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
    public function edit($data){
        // TODO: Implement index() method.
        $valitor = new Validate();
        $valitor->addColumn('id', 'id')->required('更改id不能为空');
        $valitor->addColumn('name', '目录名')->required('目录名不为空')->betweenLen(2,10,'目录名长度为2到10');
        $valitor->addColumn('parent_id', '父类id')->required('父类id不为空')->integer();
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