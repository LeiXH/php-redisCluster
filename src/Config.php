<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/14
 * Time: 2:18 PM
 */

namespace redis;


class Config
{

    private $ip;

    private $params;

    public function __construct($ip = null, $params = null)
    {

        $this->ip = $ip;

        $this->params = $params;
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
    public function getParams()
    {
        return $this->params;
    }
}