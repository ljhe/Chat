<?php
/**
 * 连接 redis 的单例类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/17
 * Time: 16:06
 */
class HelperRedis {

    private static $redisInstance;

    /**
     * 私有化构造函数
     * 原因：防止外界调用构造新的对象
     */
    private function __construct(){}

    /**
     * 私有属性的克隆方法，防止被克隆
     */
    private function __clone(){}

    /**
     * 获取唯一实例HelperRedis
     */
    public static function getRedisConn(){
        if(!self::$redisInstance instanceof self){
            self::$redisInstance = new self;
        }
        // 获取当前单例
        $temp = self::$redisInstance;
        // 调用私有化方法
        return $temp->connRedis();
    }

    /**
     * 连接redis的私有化方法
     * @return redis
     */
    private static function connRedis()
    {
        try {
            $redis_ocean = new Swoole\Coroutine\Redis();
            $redis_ocean->connect('127.0.0.1', 6379);
        }catch (Exception $e){
            echo $e->getMessage().'<br/>';
        }
        return $redis_ocean;
    }
}