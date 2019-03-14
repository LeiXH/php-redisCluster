<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/13
 * Time: 9:05 AM
 */

namespace redis;

class ConnectPool
{
    private $pool = array();

    /**
     * ConnectPool constructor.
     * @param $config
     * @param $params$redis['clients'] = array(
    '172.16.154.235:6479',
    '172.16.154.235:6480',
    '172.16.154.235:6481',
    '172.16.154.235:6482',
    '172.16.154.235:6483',
    '172.16.154.235:6484',
    );

    $redis['options'] = array(
    'cluster' => 'redis',
    'parameters' => [
    'password' => 'madzd56sl'
    ],
    );
     */

    public function __construct($ips, $params)
    {
        foreach ($ips as $val) {
            $config = new Config($val, $params);

            $this->pool[] = new Connect($config);
        }
    }

    public function getPool()
    {
        return $this->pool;
    }

    /*public function  getDefault()
    {
        if (!empty($this->pool['default'])) {
            $op = new Operater($this->pool['default']);
            if ($op->set('test', 'ray') == false)
            {
                $this->pool['default'] = null;
            }else
            {
                $op->del('test');
            }
        }

        if (empty($this->pool['default'] ) || $this->pool['default']  == null) {
            foreach ($this->pool as $key => $value)
            {
              //试探发
                $op = new Operater($value);
                if ($op->set('test', 'ray') === false)
                {
                    continue;
                }
                else
                {
                    $op->del('test');
                    $this->pool['default'] = $value;
                    break;
                }
            }
        }

        if ($this->pool['default'] == null)
            throw new phpRedisClusterException('cannot connect redis');
        return $this->pool['default'];
    }*/
}