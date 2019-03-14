<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/11
 * Time: 2:44 PM
 */

namespace redis;

class Response
{
    private $response;

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function parseResponse()
    {
        // TODO: Implement parseResponse() method.
        if ($this->response[0] == '-') {
            $this->response = ltrim($this->response, '-');
            list($errstr, $this->response) = explode("\r\n", $this->response, 2);
            $res = explode(" ", $errstr);
            if ($res) {
                if (isset($res[0]) && $res[0] == 'MOVED') return $res;
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

    public function resToArray()
    {
        $ret = array();
        $this->response = ltrim($this->response, '*');
        list($count, $this->response) = explode("\r\n", $this->response, 2);
        for($i = 0; $i < $count; $i++)
        {
            $tmp = $this->parseResponse();
            $ret[] = $tmp;
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

}