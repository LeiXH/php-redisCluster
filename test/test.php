<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/5
 * Time: 9:51 AM
 */

require_once __DIR__."/../autoload.php";
require_once __DIR__."/Config.php";


use redis\Clients;

try{

    $client = new Clients($redis['clients'], $redis['options']);

    $client->lpush('anotest', 'this is ray test');
    $test = $client->lpop('anotest');
}
catch (Exception $exception){
    var_dump($exception->getMessage());
    exit;
}

echo $test;
exit;