<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/19
 * Time: 2:52 PM
 */

namespace App\HttpController;

use App\Model\File\FileModel;
use App\Model\QiniuPlugin\QiniuPluginModel;
use App\Service\Env;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utils\Date;

class File extends Token
{

    /**
     * 列表
     * @author: zzhpeng
     * Date: 2019/4/28
     * @return bool|void
     * @throws \Throwable
     */
    public function index(){
        try{
            $conditions = $this->getConditions();

            //用账户查找用户,验证是否存在该用户
            $file = MysqlPool::invoke(function (MysqlObject $db) use($conditions) {
                $fileModel = new FileModel($db);
                $result = $fileModel->getAll($conditions);
                return $result;
            });

            return $this->successResponse($file);
        }catch (\Exception $exception){
            return $this->failResponse($exception->getMessage());
        }
    }

    /**
     * 条件
     * @author: zzhpeng
     * Date: 2019/4/28
     * @return array
     * @throws \Exception
     */
    function getConditions(){
        $where = [];
        $condition = [];
//        $condition = [
//            'where'=>[
//                [
//                    'account',$param['account']
//                ]
//            ]
//        ];
        $uid = $this->userId();
        array_push($where,['user_id',$uid]);

        $param = $this->request()->getRequestParam();

        if(isset($param['catalog_id'])){
            array_push($where,['catalog_id',$param['catalog_id']]);
        }

        if(!empty($where)){
            $condition = [
                'where'=>$where
            ];
        }

        return $condition;
    }


    /**
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return bool
     * @throws \Throwable
     */
    public function edit(){
        try{
            $userId = $this->userId();
            $param = $this->request()->getRequestParam();
            $validate = ($validate = new CatalogValidate())->edit($param);

            if($validate){
                throw new \Exception($validate);
            }

            MysqlPool::invoke(function (MysqlObject $db) use($param,$userId) {
                $catalogModel = new CatalogModel($db);
                $catalogBean = new CatalogBean();

                //验证
                $catalog = $catalogModel->getOne([
                    'where'=>[
                        [
                            'user_id',$userId,
                        ], [
                            'id',$param['id'],
                        ]
                    ]
                ]);

                if(empty($catalog)){
                    throw new \Exception('数据不存在');
                }
                unset($catalog);

                //查找数据是否有该目录名的数据
                $isExist = $catalogModel->getOne([
                    'where'=>[
                        [
                            'name',$param['name']
                        ],
                        [
                            'user_id',$userId
                        ],
                        [
                            'id',$param['id'],'<>'
                        ],
                        [
                            'parent_id',$param['parent_id'],
                        ],
                    ]
                ]);

                if(!empty($isExist)){
                    throw new \Exception('目录名已存在');
                }

                //更新数据
                $updateData['name'] = $param['name'];
                $updateData['parent_id'] = $param['parent_id'];
                $updateData['create_time'] = Date::defaultDate();

                $catalogBean->setId($param['id']);

                $result = $catalogModel->update($catalogBean, $updateData);
                if ($result === false) {
                    throw new \Exception('目录名更改失败');
                }
            });
            return $this->successResponse();
        }catch (\Exception $exception){
            return $this->failResponse($exception->getMessage());
        }
    }

    /**
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return bool
     * @throws \Throwable
     */
    public function delete(){
        try{
            $userId = $this->userId();
            $param = $this->request()->getRequestParam();
            $validate = ($validate = new CatalogValidate())->delete($param);

            if($validate){
                throw new \Exception($validate);
            }

            MysqlPool::invoke(function (MysqlObject $db) use($param,$userId) {
                $catalogModel = new CatalogModel($db);
                $catalogBean = new CatalogBean();

                //验证 todo 在使用的目录不给删除
                $catalog = $catalogModel->getOne([
                    'where'=>[
                        [
                            'user_id',$userId,
                        ], [
                            'id',$param['id'],
                        ]
                    ]
                ]);

                if(empty($catalog)){
                    throw new \Exception('数据不存在');
                }
                unset($catalog);

                $catalogBean->setId($param['id']);

                $result = $catalogModel->delete($catalogBean);
                if ($result === false) {
                    throw new \Exception('删除失败');
                }
            });
            return $this->successResponse();
        }catch (\Exception $exception){
            return $this->failResponse($exception->getMessage());
        }
    }


    public function getUpToken(){
        try{
            $userId = $this->userId();
            $qiniuPlugin = MysqlPool::invoke(function (MysqlObject $db) use($userId) {
                $qiniuPluginModel = new QiniuPluginModel($db);
                return $qiniuPluginModel->getOne([
                    'where'=>[
                        [
                            'user_id',$userId,
                        ]
                    ]
                ]);

            });
            if(!$qiniuPlugin){
                throw new \Exception('请先配置七牛');
            }
            $policy = array(
                'callbackUrl' => Env::getInstance()->get('QINIU.CALLBACK_URL') . '/service/fileCallBack',
                'callbackBody' => '{"fname":"$(fname)","fkey":"$(key)","desc":"$(x:name)","uid":' . $userId . ',"domain":' . '"' .$qiniuPlugin['domain'] . '"' . '}'
            );
            $auth = new \Qiniu\Auth($qiniuPlugin['accessKey'], $qiniuPlugin['secretKey']);
            $upToken = $auth->uploadToken($qiniuPlugin['bucket'], null, 3600, $policy);
            return $this->successResponse([
                'uptoken'=>$upToken,
                'domain'=>$qiniuPlugin['domain'],
                'region'=>$qiniuPlugin['zone'],
                'fname'=>'picture',
                ]);
        }catch (\Exception $e){
            return $this->failResponse($e->getMessage());
        }

    }

}