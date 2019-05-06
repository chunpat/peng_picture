<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:27 PM
 */

namespace App\HttpController;


use App\Enum\Code;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;

class BaseController extends Controller
{
    public function index( )
    {
        // TODO: Implement index() method.
    }

//    function onRequest(?string $action): ?bool
//    {
//        //获取配置的TOKEN_ACTION_EXPIRE
//        $excepts = Config::getInstance()->getConf('ACTION_EXCEPTS');
//        if (parent::onRequest($action)) {
//            //判断是否登录
//            if (!in_array($action,$excepts)) {
//                try{
//                    $jwt = \App\Service\Token::LoginVerifyToken($this->request()->getHeaders());
//                    var_dump($jwt);
//                    //登录后操作
//
//
//                    $this->successResponse();
//                    return false;
//                }catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
//                    $this->failResponse('签名不正确');
//                }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
//                    $this->failResponse('签名在某个时间点之后才能用');
//                }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
//                    $this->failResponse('access_token过期');
//                }catch(\Exception $e) {  //其他错误
//                    $this->failResponse($e->getMessage());
//                }
//            }
//            return true;
//        }
//        return false;
//    }

    /**
     * 返回成功
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param null $result
     * @param null $msg
     * @param int  $statusCode
     *
     * @return bool
     */
    protected function successResponse($result = null, $msg = '成功', $statusCode = 200)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "error_code" => $statusCode,
                "result" => $result,
                "error_msg" => $msg
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 返回失败
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param string $msg
     * @param int    $statusCode
     * @param null   $result
     *
     * @return bool
     */
    protected function failResponse($msg = '接口返回错误',$statusCode = 400,$result = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "error_code" => $statusCode,
                "result" => $result,
                "error_msg" => $msg
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }
}