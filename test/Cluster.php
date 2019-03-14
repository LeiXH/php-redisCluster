<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/4
 * Time: 4:01 PM
 */


$redis = stream_socket_client('tcp://172.16.154.235:6479', $errno, $errstr, 5);

if (!$redis)
{
    die('连接redis服务器失败: ' . $errstr);
}

// 查询代码....

$cmd = "*3\r\n$3\r\nset\r\n$6\r\nfoobar\r\n$5\r\nredis\r\n"; //dbsize
fwrite($redis, $cmd, strlen($cmd));
$ret = fread($redis, 4096);
echo $ret;
echo "----------------------\r\n";
