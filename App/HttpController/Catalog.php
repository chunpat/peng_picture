<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/19
 * Time: 2:54 PM
 */

namespace App\HttpController;


use App\Model\Catalog\CatalogModel;
use App\Model\Catalog\CatalogBean;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utils\Date;
use App\Validate\CatalogValidate;

class Catalog extends Token
{
    /**
     * 列表
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return bool|void
     * @throws \Throwable
     */
    public function index(){
        try{
            $conditions = $this->getConditions();

            //用账户查找用户,验证是否存在该用户
            $catalog = MysqlPool::invoke(function (MysqlObject $db) use($conditions) {
                $catalogModel = new CatalogModel($db);
                $result = $catalogModel->getAll($conditions);
                return $result;
            });

            return $this->successResponse($catalog);
        }catch (\Exception $exception){
            return $this->failResponse($exception->getMessage());
        }
    }

    /**
     * 条件
     * @author: zzhpeng
     * Date: 2019/4/19
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
        if(isset($param['name'])){
            array_push($where,['name','%' . $param['name'] . '%','like']);
        }

        if(isset($param['parent_id'])){
            array_push($where,['parent_id',$param['parent_id']]);
        }else{
            array_push($where,['parent_id',0]);
        }

        if(!empty($where)){
            $condition = [
                'where'=>$where
            ];
        }

        return $condition;
    }

    /**
     * 添加
     * @author: zzhpeng
     * Date: 2019/4/19
     * @return bool
     * @throws \Throwable
     */
    public function save(){
        try{
            $userId = $this->userId();
            $param = $this->request()->getRequestParam();
            $validate = ($validate = new CatalogValidate())->add($param);

            if($validate){
                throw new \Exception($validate);
            }

            MysqlPool::invoke(function (MysqlObject $db) use($param,$userId) {
                $catalogModel= new CatalogModel($db);
                $catalogBean = new CatalogBean();

                //查找数据是否有该目录名的数据
                $isExist = $catalogModel->getOne([
                    'where'=>[
                        [
                            'name',$param['name']

                        ],
                        [
                            'user_id',$userId,
                        ],
                        [
                            'parent_id',$param['parent_id'],
                        ],
                    ]
                ]);
                if(!empty($isExist)){
                    throw new \Exception('目录名已存在');
                }

                $catalogBean->setName($param['name']);
                $catalogBean->setParentId($param['parent_id']);
                $catalogBean->setUserId($userId);
                $catalogBean->setCreateTime(Date::defaultDate());
                $result = $catalogModel->register($catalogBean);
                if ($result === false) {
                    throw new \Exception('名录生成失败');
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
}
