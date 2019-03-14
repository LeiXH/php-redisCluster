<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/11
 * Time: 2:46 PM
 */

namespace redis;


class Connect
{
    private $conn;

    private $ip;

    private $param;

    public function __construct(Config $config)
    {
        $this->ip    = $config->getIp();

        $this->param = $config->getParams();

        $this->connect($config->getIp());
    }

    public function connect($host, $timeout = 30)
    {
        $this->conn = stream_socket_client("tcp://".$host, $errno, $errstr, $timeout);
        if (!$this->conn) {
            throw new phpRedisClusterException("Cannot connect redis".$errstr);
        }
    }

    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }


    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }

    public function close()
    {
        @stream_socket_shutdown($this->conn, STREAM_SHUT_RDWR);
        @fclose($this->conn);
        $this->conn = null;
    }

}