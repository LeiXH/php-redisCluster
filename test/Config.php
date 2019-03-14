<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/4
 * Time: 9:39 AM
 */

$redis['clients'] = array(
    'tcp://172.16.154.235:6479',
    'tcp://172.16.154.235:6480',
    'tcp://172.16.154.235:6481',
    'tcp://172.16.154.235:6482',
    'tcp://172.16.154.235:6483',
    'tcp://172.16.154.235:6484',
);

$redis['options'] = array(
    'cluster' => 'redis',
    'parameters' => [
        'password' => 'madzd56sl'
    ],
);

$config['redis_cluster'] = $redis;

$config['redis'] = "192.168.20.16";
$config['port'] = "6379";

$redis_test = [
    'ip' => '172.16.154.235:6480',
    'parameters' => [
        'password' => 'madzd56sl'
    ]
];
