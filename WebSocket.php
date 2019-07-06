<?php
/**
 * Class WebSocket
 */
class WebSocket {

    const HOST = '0.0.0.0';     // 监听的 IP 地址, 0.0.0.0 表示监听所有地址
    const PORT = 8811;          // 端口号
    private $serv;

    public function __construct()
    {
        $this->serv = new Swoole\WebSocket\Server(self::HOST, self::PORT);

        $this->serv->on('open', array($this, 'onOpen'));
        $this->serv->on('message', array($this, 'onMessage'));
        $this->serv->on('close', array($this, 'onClose'));
        $this->serv->start();
    }

    /**
     * 连接成功的回调方法
     * @param $serv
     * @param $request
     */
    public function onOpen($serv, $request)
    {
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
            $fd == $frame->fd ? '0' : '1';
            $serv->push($fd, $this->param($frame->data,$fd,$frame->fd));
        }
        //  向发送消息的客户端推送数据
        //  $serv->push($frame->fd, $frame->data);
    }

    /**
     * 关闭连接的回调方法
     * @param $serv
     * @param $fd
     */
    public function onClose($serv, $fd)
    {
        echo "client {$fd} closed\n";
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
        $fd == $frame_fd ? $data->type = 0 : $data->type = 1;
        return json_encode($data);
    }
}

new WebSocket();