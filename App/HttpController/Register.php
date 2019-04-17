<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 12:04 PM
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;
use \Firebase\JWT\JWT;

class Register extends Controller
{

    //登录验证
    public function index()
    {
    }

    //注册
    public function register(){
        // TODO: Implement index() method.
        echo 'sss';
    }

    //退出
    public function logout(){
    }
}