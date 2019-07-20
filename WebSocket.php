<?php
/**
 * Class WebSocket
 */

require "DoRedis.php";

class WebSocket {

    const HOST = '0.0.0.0';     // 监听的 IP 地址, 0.0.0.0 表示监听所有地址
    const PORT = 8811;          // 端口号
    private $serv;

    public function __construct()
    {
        $this->serv = new Swoole\WebSocket\Server(self::HOST, self::PORT);

        $this->serv->on('start', array($this, 'onStart'));
        $this->serv->on('open', array($this, 'onOpen'));
        $this->serv->on('message', array($this, 'onMessage'));
        $this->serv->on('close', array($this, 'onClose'));
        $this->serv->start();
    }

    /**
     * 连接成功时的回调方法
     */
    public function onStart()
    {
        //  创建一个新的协程,并立即执行; go 相当于 Swoole\Coroutine::create
        go(function (){
            //  下面开启的话会在下方有一个警告 fd[0] is invalid
            //DoRedis::getRedis()->del('userList');
        });
        echo 'start success';
    }

    /**
     * 连接成功的回调方法
     * @param $serv
     * @param $request
     */
    public function onOpen($serv, $request)
    {
        //  当连接成功的时候将用户信息保存到 redis 的集合中
        DoRedis::getRedis()->sAdd('userList', $request->fd);
        //  向客户端推送数据
        $this->push($serv);
        echo "server: handshake success with fd{$request->fd}\n";
    }

    /**
     * 接收到客户端发送的信息的回调方法
     * @param $serv
     * @param $frame
     */
    public function onMessage($serv, $frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        //  遍历所有连接的客户端
        foreach($serv->connections as $fd)
        {
            //  向发送消息的客户端推送数据
            $serv->push($fd, $this->param($frame->data,$fd,$frame->fd));
        }
    }

    /**
     * 关闭连接的回调方法
     * @param $serv
     * @param $fd
     */
    public function onClose($serv, $fd)
    {
        //  当连接断开的时候将用户信息从 redis 的集合中移除
        DoRedis::getRedis()->sRem('userList', $fd);
        //  向客户端推送数据
        $this->push($serv);
        echo "client {$fd} closed\n";
    }

    /**
     * 向当前 demo 客户端推送数据
     * @param $serv
     */
    private function push($serv)
    {
        //  遍历所有连接的客户端
        foreach($serv->connections as $fd)
        {
            //  向发送消息的客户端推送数据
            $serv->push($fd, DoRedis::getRedis()->sCard('userList'));
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

new WebSocket();
