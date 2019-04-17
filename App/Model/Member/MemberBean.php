<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/11/28
 * Time: 11:47 AM
 */
namespace App\Model\Member;
use EasySwoole\Spl\SplBean;
class MemberBean extends SplBean
{
    protected $id;
    protected $mobile;
    protected $name;
    protected $password;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @author: zzhpeng
     * Date: 2019/4/12
     * @param $id
     *
     * @return int
     */
    public function setId($id): int
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }
    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile): void
    {
        $this->mobile = $mobile;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}