<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:38 AM
 */

namespace App\Model\Catalog;

use EasySwoole\Spl\SplBean;

class CatalogBean extends SplBean
{
    protected $id;
    protected $name;
    protected $parent_id;
    protected $user_id;
    protected $create_time;
    protected $update_time;

    /**
     * @author: zzhpeng
     * Date: 2019/4/12
     */
    protected function initialize():void
    {
        $this->update_time = date('Y-m-d H:i:s');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }
    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId): void
    {
        $this->parent_id = $parentId;
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    /**
     * @param mixed $parentId
     */
    public function setUserId($userId): void
    {
        $this->user_id = $userId;
    }

    public function getCreateTime()
    {
        return $this->create_time;
    }

    public function setCreateTime( $createTime): void
    {
        $this->create_time = $createTime;
    }
}