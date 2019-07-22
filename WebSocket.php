<?php
/**
 * Class WebSocket
 */

require "DoRedis.php";
require "DoPush.php";

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
            DoRedis::getRedis()->del('userList');
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
        DoPush::getPush()->push($serv);
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
        //  向所有客户端推送消息
        DoPush::getPush()->pushMessage($serv, $frame);
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
        DoPush::getPush()->push($serv);
        echo "client {$fd} closed\n";
    }
}

new WebSocket();
