<?php
/**
 * Created by PhpStorm.
 * User: zzhpeng
 * Date: 2019/4/12
 * Time: 11:38 AM
 */

namespace App\Model\QiniuPlugin;

use EasySwoole\Spl\SplBean;

class QiniuPluginBean extends SplBean
{
    protected $id;
    protected $accessKey;
    protected $secretKey;
    protected $protocol;
    protected $domain;
    protected $bucket;
    protected $zone;
    protected $style_separator;
    protected $user_id;
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
     * @param mixed $accessKey
     */
    public function setAccessKey($accessKey): void
    {
        $this->accessKey = $accessKey;
    }

    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }
    /**
     * @param mixed $secretKey
     */
    public function setSecretKey($secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }
    /**
     * @param mixed $protocol
     */
    public function setProtocol($protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }
    /**
     * @param mixed $domain
     */
    public function setDomain($domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getBucket()
    {
        return $this->bucket;
    }
    /**
     * @param mixed $bucket
     */
    public function setBucket($bucket): void
    {
        $this->bucket = $bucket;
    }


    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }
    /**
     * @param mixed $zone
     */
    public function setZone($zone): void
    {
        $this->zone = $zone;
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
    public function getStyleSeparator()
    {
        return $this->style_separator;
    }
    /**
     * @param mixed $style_separator
     */
    public function setStyleSeparator($style_separator): void
    {
        $this->style_separator = $style_separator;
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