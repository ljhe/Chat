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

    private $redis;

    public function __construct()
    {
        $this->redis = HelperRedis::getRedisConn();
    }

    public function test()
    {
        //$this->redis->set('test','2');
        $val = $this->redis->get('test');
        echo $val.PHP_EOL;
    }
}