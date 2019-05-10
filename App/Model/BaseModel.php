<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/11/26
 * Time: 12:31 PM
 */
namespace App\Model;
use App\Utility\Pool\MysqlObject;
use EasySwoole\Spl\SplBean;

/**
 * model写法1
 * 通过传入mysql连接去进行处理
 * Class BaseModel
 * @package App\Model
 */
class BaseModel
{
    protected $table;
    private $db;
    function __construct(MysqlObject $dbObject)
    {
        $this->db = $dbObject;
    }
    protected function getDb():MysqlObject
    {
        return $this->db;
    }
    function getDbConnection():MysqlObject
    {
        return $this->db;
    }

    function getAll($condition = [], array $request = []): array
    {
        $page = $request['page'] ?? 1;
        $pageSize = $request['count']?? 10;
        $allow = ['where', 'orWhere', 'join', 'orderBy', 'groupBy'];
        foreach ($condition as $k => $v) {
            if (in_array($k, $allow)) {
                foreach ($v as $item) {
                    $this->getDb()->$k(...$item);
                }
            }
        }
        $list = $this->getDb()
            ->withTotalCount()
            ->get($this->table, [$pageSize * ($page - 1), $pageSize]);
        $total = $this->getDb()->getTotalCount();
        return ['total' => $total, 'list' => $list,'page' => $page];
    }

    /**
     * 获取单条数据
     * @author: zzhpeng
     * Date: 2019/4/12
     * @param $condition
     *
     * @return \EasySwoole\Mysqli\Mysqli|mixed|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    function getOne(array $condition)
    {
        $allow = ['where', 'orWhere', 'join', 'orderBy', 'groupBy'];
        foreach ($condition as $k => $v) {
            if (in_array($k, $allow)) {
                foreach ($v as $item) {
                    $this->getDb()->$k(...$item);
                }
            }
        }

        $data = $this->getDb()->getOne($this->table);
        if (empty($data)) {
            return null;
        }
        return $data;
    }

    function update(SplBean $bean, array $data)
    {
        $this->getDb()->where('id', $bean->getId())->update($this->table, $data);
        return $this->getDb()->getAffectRows();
    }

    function register(SplBean $bean)
    {
        return $this->getDb()->insert($this->table, $bean->toArray());
    }

    function delete(SplBean $bean)
    {
        return $this->getDb()->where('id', $bean->getId())->delete($this->table);
    }
}