<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 12:04 PM
 */

namespace App\HttpController;

use App\Model\User\UserBean;
use App\Model\User\UserModel;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utils\Encrypt;
use App\Validate\UserValidate;

class Register extends Token
{

    /**
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return bool|void
     * @throws \Throwable
     */
    public function index()
    {
        try{
            $data = $this->request()->getRequestParam();
            $validate = ($validate = new UserValidate())->add($data);
            if($validate){
                throw new \Exception($validate);
            }

            //用账户查找用户,验证是否存在该用户
            $account = MysqlPool::invoke(function (MysqlObject $db) use($data) {
                $userModel = new UserModel($db);
                $result = $userModel->getOne([
                    'where'=>[
                        [
                            'account',$data['account']
                        ]
                    ]
                ]);
               return $result;
            });


            if(!empty($account)){
                throw new \Exception('该账号已存在');
            }

            MysqlPool::invoke(function (MysqlObject $db) use($data) {
                $userModel = new UserModel($db);
                $userBean = new UserBean();
                $userBean->setAccount($data['account']);
                $userBean->encryptPassword($data['password']);
                $userBean->setCreateTime(date('Y-m-d H:i:s'));
                $result = $userModel->register($userBean);
                if ($result === false) {
                   throw new \Exception('注册失败');
                }
            });

            return $this->successResponse();
        }catch (\Exception $e){
            return $this->failResponse($e->getMessage());
        }

    }

    //注册
    public function register(){


    }

    //退出
    public function logout(){
    }
}