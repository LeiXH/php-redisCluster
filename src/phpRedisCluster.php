<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/4
 * Time: 7:36 PM
 */

namespace redis;
class phpRedisCluster
{
    private $conn;
    private $response;
    private $command;
    private $isPipeline = false;
    private $pipelineCmd;
    private $pipelineCount;

    public function connect($host, $timeout = 3)
    {
        $this->conn = stream_socket_client("tcp://".$host, $errno, $errstr, $timeout);
        if (!$this->conn) {
            throw new phpRedisClusterException("Cannot connect redis".$errstr);
        }
    }

    public function makeCommand($args)
    {
        $cmds = array();
        $cmds[] = '*' . count($args) . "\r\n";
        foreach($args as $arg)
        {
            $cmds[] = '$' . strlen($arg) . "\r\n$arg\r\n";
        }

        $this->command = implode($cmds);
    }

    protected function resToArray()
    {
        $ret = array();
        $this->response = ltrim($this->response, '*');
        list($count, $this->response) = explode("\r\n", $this->response, 2);
        for($i = 0; $i < $count; $i++)
        {
            $tmp = $this->decodeResult();
            $ret[] = $tmp;
        }
        return $ret;
    }

    public function decodeResult()
    {
        if ($this->response[0] == '-') {
            $this->response = ltrim($this->response, '-');
            list($errstr, $this->response) = explode("\r\n", $this->response, 2);
            $res = explode(" ", $errstr);
            if ($res) {
                throw new phpRedisClusterException($errstr);
            }
        }

        switch ($this->response[0]) {
            case '+':
            case ':':
                list($ret, $this->response) = explode("\r\n", $this->response, 2);
                $ret = substr($ret, 1);
                break;
            case '$':
                $this->response = ltrim($this->response, '$');
                list($slen, $this->response) = explode("\r\n", $this->response, 2);
                $ret = substr($this->response, 0, intval($slen));
                $this->response = substr($this->response, 2 + $slen);
                break;
            case '*':
                $ret = $this->resToArray();
                break;
        }

        return $ret;
    }

    public function getResponse() {
        $this->response = fread($this->conn, 8196);
        stream_set_blocking($this->conn, 0); // 设置连接为非阻塞
        // 继续读取返回结果
        while($buf = fread($this->conn, 8196))
        {
            $this->response .= $buf;
        }
        stream_set_blocking($this->conn, 1);
    }

    public function close()
    {
        @stream_socket_shutdown($this->conn, STREAM_SHUT_RDWR);
        @fclose($this->conn);
        $this->conn = null;
    }

    public function exec()
    {
        if (func_num_args() == 0)
        {
            throw new phpRedisClusterException("the param cannot null");
        }
        $this->makeCommand(func_get_args());

        if (TRUE === $this->isPipeline)
        {
            $this->pipelineCmd .= $this->command;
            $this->pipelineCount++;
            return;
        }

        //echo $this->command;
        fwrite($this->conn, $this->command, strlen($this->command));

        $this->getResponse();

        //echo $this->response;
        return $this->decodeResult();
    }
}