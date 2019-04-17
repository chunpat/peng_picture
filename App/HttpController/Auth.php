<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/13
 * Time: 5:48 PM
 */

namespace App\HttpController;


class Auth
{
    protected $user = [];
    protected $accessToken;
    protected $accessTokenData;
    protected $acceessTokenLogic;
    protected $request;

    // todo 设置RequestUserBean
    final protected function getRequestUser()
    {
        if( empty( $this->user ) ){
            $access_token_data = $this->getRequestAccessTokenData();
            $condition['id']   = $access_token_data['sub'];
            $user              = \App\Model\User::init()->getUserInfo( $condition );
            if( !empty( $user ) ){
                $user_auxiliary = $this->getUserInfo( $user['id'] );
                return new SplArray( array_merge( $user, $user_auxiliary ) );
            } else{
                return false;
            }
        } else{
            return $this->user;
        }
    }

}