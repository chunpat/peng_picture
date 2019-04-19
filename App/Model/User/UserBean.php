<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:38 AM
 */

namespace App\Model\User;


use App\Utils\Encrypt;
use EasySwoole\Spl\SplBean;

class UserBean extends SplBean
{
    protected $id;
    protected $account;
    protected $password;
    protected $nickname;
    protected $openid;
    protected $last_login_time;
    protected $balance;
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
    public function setId($id): int
    {
        return $this->$id;
    }
    /**
     * @param mixed $member_id
     */
    public function setAccount($account): void
    {
        $this->account = $account;
    }
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param mixed $member_id
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function encryptPassword($password): void
    {
        $this->password = Encrypt::encrypt($password);
    }


    /**
     * @return mixed
     */
    public function getOpenid()
    {
        return $this->openid;
    }
    /**
     * @param mixed $member_id
     */
    public function setOpenid($openid): void
    {
        $this->openid = $openid;
    }

    /**
     * @return mixed
     */
    public function getLastLoginTime()
    {
        return $this->last_login_time;
    }
    /**
     * @param mixed $member_id
     */
    public function setLastLoginTime($last_login_time): void
    {
        $this->last_login_time = $last_login_time;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }
    /**
     * @param mixed $member_id
     */
    public function setBalance($balance): void
    {
        $this->Balance = $balance;
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