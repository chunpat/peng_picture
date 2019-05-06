<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:38 AM
 */

namespace App\Model\File;

use EasySwoole\Spl\SplBean;

class FileBean extends SplBean
{
    protected $id;
    protected $catalog_id;
    protected $url;
    protected $host;
    protected $path;
    protected $user_id;
    protected $desc;
    protected $fname;
    protected $create_time;
    protected $update_time;

    /**
     * @author: zzhpeng
     * Date: 2019/4/12
     */
    protected function initialize():void
    {
        $this->update_time = date('Y-m-d H:i:s');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
    /**
     * @param mixed $catalogId
     */
    public function setCatalogId($catalogId): void
    {
        $this->catalog_id = $catalogId;
    }

    public function getCatalogId()
    {
        return $this->catalog_id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }
    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->user_id = $userId;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }
    /**
     * @param mixed $desc
     */
    public function setDesc($desc): void
    {
        $this->desc = $desc;
    }

    /**
     * @return mixed
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * @param mixed $fname
     */
    public function setFname($fname): void
    {
        $this->fname = $fname;
    }

    public function getCreateTime()
    {
        return $this->create_time;
    }

    public function setCreateTime( $createTime): void
    {
        $this->create_time = $createTime;
    }
}