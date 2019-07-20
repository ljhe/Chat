<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/17
 * Time: 17:08
 */

require "HelperRedis.php";

class DoRedis
{
    private static $doRedis;
    private $redis;

    /**
     * 返回一个 doRedis 类的对象
     * @return DoRedis
     */
    public static function getRedis()
    {
        if (!self::$doRedis instanceof self) {
            self::$doRedis = new self;
        }
        return self::$doRedis;
    }

    /**
     * 连接 redis
     * DoRedis constructor.
     */
    private function __construct()
    {
        $this->redis = HelperRedis::getRedisConn();
    }

    /**
     * key 存在时删除 key
     * @param $key
     * @return int
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }

    /**
     * 向一个 redis 集合中插入数据
     * @param $key
     * @param $value
     * @return int
     */
    public function sAdd($key, $value)
    {
        return $this->redis->sAdd($key, $value);
    }

    /**
     * 将某个值从 redis 集合中移除
     * @param $key
     * @param $value
     * @return int
     */
    public function sRem($key, $value)
    {
        return $this->redis->sRem($key, $value);
    }

    /**
     * 返回集合中元素的数量
     * @param $key
     * @return int
     */
    public function sCard($key)
    {
        return $this->redis->sCard($key);
    }
}