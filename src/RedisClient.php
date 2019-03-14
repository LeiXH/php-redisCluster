<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/2/27
 * Time: 4:58 PM
 */
namespace redis;

/**
 * @method int get_test($array);
 * Class RedisClient
 * @package manager\redis
 */
class RedisClient
{
    private $redis;

    public function __construct($config)
    {
        $this->redis = new \Redis();
        $this->redis->connect($config['redis'], $config['port']);
    }

    /**
     * 析构函数
     */
    function __destruct()
    {
        $this->redis = null;
    }
    /**
     * 设置值
     */
    function set($key, $value)
    {
        $result = $this->redis->set($key, serialize($value));
        return $result;
    }

    public function expire($key, $time)
    {
        $this->redis->expire($key, $time);
    }

    public function hSet($key, $domain, $val)
    {
        return $this->redis->hset($key, $domain, $val);
    }

    public function hGet($key, $domain)
    {
        return $this->redis->hget($key, $domain);
    }

    /**
     * 获取数据
     */
    function get($key) {
        $result = unserialize($this->redis->get($key));
        return $result;
    }

    /**
     * 获取list长度
     */
    function llen($key) {
        $result = $this->redis->llen($key);
        return $result;
    }

    /**
     * 获取list数据
     */
    function lrange($key, $offset, $limit) {
        $result = $this->redis->lrange($key, $offset, $limit);
        return $result;
    }

    /**
     * 删除数据
     */
    function delete($key) {
        return $this->redis->delete($key);
    }

    /**
     * 清空数据
     */
    function flushAll() {
        return $this->redis->flushAll();
    }

    function watch($key) {
        return $this->redis->watch($key);
    }

    function multi() {
        return $this->reids->multi();
    }

    function incr($key) {
        return $this->redis->incr($key);
    }

    function decr($key) {
        return $this->redis->decr($key);
    }

    function exec() {
        return $this->redis->exec();
    }

    function secure_incr($key, $value)
    {
        $this->watch($key);
        $this->multi();
        $this->incr($key);
        if ($value + 1 == $this->exec()) {
            return true;
        }
        return false;
    }

    function secure_incr_by($key, $value, $incr_value)
    {
        $this->watch($key);
        $this->multi();
        $this->set($key, $value + $incr_value);
        if ($value + $incr_value == $this->exec()) {
            return true;
        }
        return false;
    }

    function sMembers($key){
        $result = $this->redis->sMembers($key);
        return $result;
    }
    function sRem( $key, $member1, $member2 = null, $memberN = null ){
        return $this->redis->sRem( $key, $member1, $member2, $memberN );
    }
    function sAdd($key,$value1, $value2 = null, $valueN = null){
        return $this->redis->sAdd($key,$value1, $value2, $valueN);
    }
    function incrBy($key,$num){
        $this->redis->incrBy($key,$num);
    }

    function close()
    {
        $this->redis->close();
    }
}