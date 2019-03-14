<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/11
 * Time: 4:28 PM
 */

require_once __DIR__."/Config.php";
require_once __DIR__."/../autoload.php";

use redis\ConnectInstance;

try {
    $client =new ConnectInstance($redis_test);
    $client->set('test',"ray test");
    $client->expire('test', 60);
    $test = $client->get('test');
} catch (Exception $exception)
{
    var_dump($exception->getMessage());
    exit;
}


var_dump($test);
exit;