<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-03-05
 * Time: 20:51
 */
namespace App\HttpController;

/**
 * model 1写法控制器
 * Class Index
 * @package App\HttpController
 */
class Index extends Token
{
    /**
     * model写法1操作数据库
     * index
     * @author Tioncico
     * Time: 14:38
     */
    function index()
    {
        try{
            $user = $this->getRequestUser();
            $this->successResponse($user);
            // TODO: Implement index() method.
        }catch (\Exception $exception){
            $this->failResponse($exception->getMessage(),$exception->getCode());
        }

    }
    function add()
    {

    }
}