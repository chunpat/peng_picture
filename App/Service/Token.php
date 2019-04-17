<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 5:21 PM
 */

namespace App\Service;


use App\Enum\Scope;
use Firebase\JWT\JWT;

class Token
{
    const KEY = 'ffdsfsd@4_45';
    const ROLE_ACCESS_EXPIRE_TIME = 7200;
    const ROLE_REFRESH_EXPIRE_TIME = 86400 * 30;
    const TOKEN_TYPE = 'bearer';

    /**
     * 生成token
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param $user
     *
     * @return array
     */
    public static function generateToken($user){
        $key = self::KEY; //key
        $time = time(); //当前时间

        //公用信息
        $token = [
            'iss' => 'https://www.zzhpeng.cn', //签发者 可选
            'iat' => $time, //签发时间
            'uuid' => $user['id']//自定义信息，不要定义敏感信息
        ];

        $access_token = $token;
        $access_token['scopes'] = Scope::ROLE_ACCESS; //token标识，请求接口的token
        $access_token['exp'] = $time+ self::ROLE_ACCESS_EXPIRE_TIME; //access_token过期时间,这里设置2个小时

        $refresh_token = $token;
        $refresh_token['scopes'] = Scope::ROLE_REFRESH; //token标识，刷新access_token
        $refresh_token['exp'] = $time+ self::ROLE_REFRESH_EXPIRE_TIME; //access_token过期时间,这里设置30天

        $jsonList = [
            'access_token'=>JWT::encode($access_token,$key),
            'refresh_token'=>JWT::encode($refresh_token,$key),
            'token_type'=>self::TOKEN_TYPE //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
        ];

        return $jsonList;
    }

    /**
     * 验证
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param $header
     *
     * @return array
     * @throws \Exception
     */
    public static function verifyToken(array $header) : array {
        //取出access_token
        list($bearer,$accessToken) = explode(' ',$header['authorization'][0]);
        if($bearer !== self::TOKEN_TYPE){
            throw new \Exception('authorization认证格式有误');
        }

        JWT::$leeway = 60;//当前时间减去60，把时间留点余地
        //key要和签发的时候一样
        $decoded = JWT::decode($accessToken, self::KEY, ['HS256']); //HS256方式，这里要和签发的时候对应
        $arr = (array)$decoded;
        return $arr;
        //Firebase定义了多个 throw new，我们可以捕获多个catch来定义问题，catch加入自己的业务，比如token过期可以用当前Token刷新一个新Token
    }

    /**
     * 登录验证
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param array $header
     *
     * @return array
     * @throws \Exception
     */
    public static function getAccessToken(array $header){
        $jwt = self::verifyToken($header);
        //权限方位
        if(!self::checkRoleAccessToken($jwt['scopes'])){
            throw new \Exception('scope权限有误');
        }

        return $jwt;
    }

    /**
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param string $scope
     *
     * @return bool
     */
    public static function checkRoleRefreshToken(string $scope) :  bool {
        return Scope::ROLE_REFRESH == $scope;
    }

    /**
     * @author: zzhpeng
     * Date: 2019/4/13
     * @param string $scope
     *
     * @return bool
     */
    public static function checkRoleAccessToken(string $scope) :  bool {
        return Scope::ROLE_ACCESS == $scope;
    }

}