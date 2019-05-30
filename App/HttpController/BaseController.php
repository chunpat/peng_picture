<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:27 PM
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Request;

class BaseController extends Controller
{
    public function index( )
    {
        // TODO: Implement index() method.
    }

    /**
     * BaseController constructor.
     */
    function __construct()
    {
       parent::__construct();
//       var_dump($_SERVER);
    }

    /**
     * 返回成功
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param array $result
     * @param string $msg
     * @param int  $statusCode
     *
     * @return bool
     */
    protected function successResponse($result = [], $msg = '成功', $statusCode = 200)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "error_code" => $statusCode,
                "result" => $result,
                "error_msg" => $msg
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
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
     * @param int    $httpCode
     * @param null   $result
     *
     * @return bool
     */
    protected function failResponse($msg = '接口返回错误',$httpCode = 400,$result = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "error_code" => 400,
                "result" => $result,
                "error_msg" => $msg
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($httpCode);
            return true;
        } else {
            return false;
        }
    }
}