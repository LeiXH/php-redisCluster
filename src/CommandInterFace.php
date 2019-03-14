<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/5
 * Time: 9:13 AM
 */

namespace redis;

interface CommandInterFace
{
    public function makeCommand($args);
}