<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/13
 * Time: 9:18 AM
 */

namespace redis;


/**
 * @get($key)
 * Class Operater
 * @package redis
 */
class Operater extends AbstractOperate
{
    private $comm;

    private $response;

    private $conn;  //单个redis连接实例

    public function __construct(Connect $connect)
    {
        $this->conn = $connect;

        if (isset($connect->getParam()['parameters']['password']) && !empty($connect->getParam()['parameters']['password']))
        {
            self::auth($connect->getParam()['parameters']['password']);
        }
    }


    public function __call($name, $arguments)
    {

        // TODO: Implement __call() method.
        $this->comm = new Command($name, $arguments);

        $this->response = new Response($this->conn->getConn());

        fwrite($this->conn->getConn(), $this->comm->getCommand(), strlen($this->comm->getCommand()));

        $this->response->getResponse();

        return $this->response->parseResponse();

    }

}