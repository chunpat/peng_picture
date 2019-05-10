<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 2:35 PM
 */

namespace App\HttpController;

use App\Model\User\UserModel;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utils\Encrypt;

class Token extends BaseController
{
    protected $user = [];
    protected $userId = 0;
    protected $accessToken;
    protected $request;

    //登录验证
    public function index()
    {
    }

    // todo 设置RequestUserBean
    final protected function getRequestUser()
    {
//        try{
            if( empty( $this->user ) ){
                $access_token_data = $this->getRequestAccessToken();
                $id   = $access_token_data['uuid'];
                $this->user = $this->getUserInfo($id);
                if( empty( $this->user ) ){
                    throw new \Exception('没有该用户信息');
                }
                return $this->user;
            } else{
                return $this->user;
            }
//        }catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
//            $this->failResponse('签名不正确');
//        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
//            $this->failResponse('签名在某个时间点之后才能用');
//        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
//            $this->failResponse('access_token过期');
//        }catch (\Exception $exception){
//            $this->failResponse($exception->getMessage());
//        }

    }

    /**
     * 获取userId
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return int
     * @throws \Exception
     */
    final protected function userId(){
        if(empty($this->userId)){
            $this->userId = $this->getRequestUser()['profile']['id'];
        }
        return $this->userId;
    }

    final protected function getRequestAccessToken() : ?array
    {
        if( $this->accessToken ){
            return $this->accessToken;
        } else{
            $jwt = \App\Service\Token::getAccessToken($this->request()->getHeaders());
            return $jwt;
        }
    }

    /**
     * 获得用户的相关信息
     * @param $user_id
     * @return array
     */
    final protected function getUserInfo( $userId )
    {
        $data                 = [];

        $data['profile']      =  MysqlPool::invoke(function (MysqlObject $db) use ($userId){
            return (new UserModel($db))->getOne(
            [
                'where'=>[
                    ['id',$userId]
                ]
            ]
            );
        });
//        $data['assets']       = \App\Model\UserAssets::init()->getUserAssetsInfo( $condition );
        return $data;
    }

    /**
     * https://blog.csdn.net/cjs5202001/article/details/80228937
     *
     * access_token：请求接口的token
     *
     * refresh_token：刷新access_token
     *
     * 举个例子：比如access_token设置2个小时过期，refresh_token设置7天过期，2小时候后，access_token过期，但是refresh_token还在7天以内，那么客户端通过refresh_token来服务端刷新，
     * 服务端重新生成一个access_token；如果refresh_token也超过了7天，那么客户端需要重新登录获取access_token和refresh_token。
     *
     * access_token中设置：scopes:role_access
     * refresh_token中设置：scopes:role_refresh
     *
     * @author: zzhpeng
     * Date: 2019/4/12
     */
    public function authorization()
    {
        try{
            //验证  是否有账户密码
            $params = $this->request()->getParsedBody();
            if(!isset($params['account']) || !isset($params['password'])){
                throw new \Exception('缺少参数');
            }
            $account = $params['account'];

            $data = MysqlPool::invoke(function (MysqlObject $db) use ($account){
                $userModel = new UserModel($db);
                //new 一个条件类,方便传入条件

                return $userModel->getOne(['where'=>[
                    ['account',$account]
                ]]);
            });
            if(!$data || Encrypt::encrypt(($params['password'])) !== $data['password']){
                throw new \Exception('账号不存在或密码不正确');
            }

            $token = \App\Service\Token::generateToken($data);
            $this->successResponse($token);
        }catch (\Exception $exception){
            $this->failResponse($exception->getMessage());
        }
    }


}