<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/13
 * Time: 9:34 AM
 */

namespace redis;



class Clients
{
    private $pool;

    private $operator;

    public function __construct($clients, $options)
    {
        $ips = $this->decodeClients($clients);
        $connect_pool = new ConnectPool($ips, $options);
        $this->pool = $connect_pool->getPool();

    }


    public function __call($name, $arguments)
    {
        foreach ($this->pool as $value) {
            $this->operator = new Operater($value);
            $res = $this->operator->$name($arguments);
            if (isset($res[0]) && $res [0] == 'MOVED') continue;
            else break;
        }
        if (empty($res) || (isset($res['0']) && $res[0] == 'MOVED'))
            throw new phpRedisClusterException('cannot connect redis cluster!');

        return $res;
    }

    public function decodeClients($clients)
    {
        $res = array();
        foreach ($clients as $val)
        {
           $mid =  explode(':', $val);
           if (!isset($mid[0]) || $mid[0] != 'tcp') throw new phpRedisClusterException('error protocol');
           if (isset($mid[1])) {
               $ip = ltrim($mid[1], "//");

               $res[] = $ip.":".$mid[2];
           }
           else throw new phpRedisClusterException('error config');
        }
        return $res;
    }
}