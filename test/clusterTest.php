<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/5
 * Time: 2:26 PM
 */

require_once __DIR__."/../autoload.php";
require_once __DIR__."/Config.php";

use PHPUnit\Framework\TestCase;
use redis\phpRedisCluster;
class clusterTest extends TestCase
{
    private $config;
    public function testConnect()
    {
        try {
            $conn = new phpRedisCluster();
            $conn->connect("172.16.154.235:6479");
        } catch (\redis\phpRedisClusterException $e) {
            var_dump($e->getMessage());
        }

        $re = $conn->exec("auth", "madzd56sl");
        $conn->close();
        $this->assertEquals("+OK", $re);
    }

    public function testGet()
    {
        try {
            $conn = new phpRedisCluster();
            $conn->connect("172.16.154.235:6479");
        } catch (\redis\phpRedisClusterException $e) {
            var_dump($e->getMessage());
        }

        $conn->exec("auth", "madzd56sl");
        $re = $conn->exec("get", "test");
        $conn->close();
        $this->assertEquals("this is test", $re);
    }

}