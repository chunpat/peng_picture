<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/28
 * Time: 11:59 AM
 */

namespace App\HttpController;

use App\Model\File\FileBean;
use App\Model\File\FileModel;
use App\Model\QiniuPlugin\QiniuPluginModel;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utils\Date;
use App\Utils\Encrypt;
use App\Validate\FileValidate;

class Service extends BaseController
{
    /**
     * 添加
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return bool
     * @throws \Throwable
     */
    public function fileCallBack(){
        try{
//            $param = [
//                '{"fname":"u=294933166,3288490880&fm=170&s=6500F51253B37FB10E7154C40300A030&w=440&h=410&img_JPG","fkey":"u=294933166,3288490880&fm=170&s=6500F51253B37FB10E7154C40300A030&w=440&h=410&img_JPG","desc":"u=294933166,3288490880&fm=170&s=6500F51253B37FB10E7154C40300A030&w=440&h=410&img","key":"MDAwMDAwMDAwMMeugZOUiYqhh511ZK6lrdDHZWmbxYxon4GgfqWYfrPcxa_nxX9jsK6ZimGirtDTm8Z7ep/Goperi2lon5qHnqI"}'
//                =>''
//            ];

            //json处理
            $param = $this->request()->getRequestParam();

            if(!$param){
                throw new \Exception('缺少参数');
            }
            $param = \Qiniu\json_decode(preg_replace('/_(jpg|JPG|jpeg|JPEG|gif|GIF|bmp|BMP|bnp|BNP|png|PNG)/',".\${1}",array_keys($param)[0]),true);

            $validate = ($validate = new FileValidate())->qadd($param);
            if($validate){
                throw new \Exception($validate);
            }

            $qiniuPlugin = MysqlPool::invoke(function (MysqlObject $db) use($param) {
                $fileModel= new FileModel($db);
                $fileBean = new FileBean();

                $qiniuPluginModel= new QiniuPluginModel($db);

//                //查找数据是否有该目录名的数据
                $isExist = $fileModel->getOne([
                    'where'=>[
                        [
                            'fname',$param['fname']

                        ],
                        [
                            'user_id',$param['uid'],
                        ],
                        [
                            'catalog_id',$param['catalog_id'] ?? 0,
                        ],
                    ]
                ]);

                $qiniuPlugin = $qiniuPluginModel->getOne([
                    'where'=>[
                        [
                            'user_id',$param['uid']

                        ]
                    ]
                ]);

                //数据库不存在该数据，创建
                if(empty($isExist)){
                    $fileBean->setUserId($param['uid']);
                    $fileBean->setFname($param['fname']);
                    $fileBean->setUrl($qiniuPlugin['domain'] . '/' . $param['fkey']);
                    $fileBean->setPath($param['fkey']);
                    $fileBean->setHost($qiniuPlugin['domain']);
                    $fileBean->setDesc($param['desc']);
                    $fileBean->setCatalogId($param['catalog_id'] ?? 0);
                    $fileBean->setCreateTime(Date::defaultDate());
                    $result = $fileModel->register($fileBean);
                }

                return $qiniuPlugin;

            });
            return $this->successResponse([
                'hash'=>'5555',
                'key'=>$param['fkey'],
                'style_separator'=>$qiniuPlugin['style_separator'],
            ]);
        }catch (\Exception $exception){
//            throw $exception;
            //todo 日志
            return $this->failResponse($exception->getMessage());
        }
    }
}