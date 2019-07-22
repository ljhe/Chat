<?php
/**
 * 向该 demo 页面中推送数据的单例类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/22
 * Time: 15:11
 */

class DoPush
{
    private static $push;

    /**
     * 单例类
     * @return DoPush
     */
    public static function getPush()
    {
        if (!self::$push instanceof self) {
            self::$push = new self;
        }
        return self::$push;
    }

    /**
     * 向当前 demo 客户端推送用户信息
     * @param $serv
     */
    public function push($serv)
    {
        //  遍历所有连接的客户端
        foreach($serv->connections as $fd)
        {
            if ($fd <= 0) {
                continue;
            }
            //  向发送消息的客户端推送数据
            $serv->push($fd, DoRedis::getRedis()->sCard('userList'));
        }
    }

    /**
     * 向当前 demo 客户端推送聊天信息
     * @param $serv
     * @param $frame
     */
    public function pushMessage($serv, $frame)
    {
        //  遍历所有连接的客户端
        foreach($serv->connections as $fd)
        {
            if ($fd <= 0) {
                continue;
            }
            //  向发送消息的客户端推送数据
            $serv->push($fd, $this->param($frame->data,$fd,$frame->fd));
        }
    }

    /**
     * 拼接当前 demo 所需的数据
     * @param $data
     * @param $fd
     * @param $frame_fd
     * @return false|string
     */
    private function param($data,$fd,$frame_fd)
    {
        $data = json_decode($data);
        $fd == $frame_fd ? $data->status = 0 : $data->status = 1;
        $data->time = date('Y-m-d H:i:s', time());
        return json_encode($data);
    }
}