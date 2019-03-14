<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/3/5
 * Time: 9:17 AM
 */

namespace redis;

class Command implements CommandInterFace
{
    private $command;

    public function __construct($name, $arguments)
    {
        $mid[] = $name;

        foreach ($arguments as $val)
        {
            if (is_array($val)) {
                foreach ($val as $value)
                {
                    $mid [] = $value;
                }
            } else
            {
                $mid [] = $val;
            }
        }
        $this->makeCommand($mid);
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function makeCommand($args)
    {
        // TODO: Implement makeCommand() method.
        $cmds = array();
        $cmds[] = '*' . count($args) . "\r\n";
        foreach($args as $arg)
        {
            $cmds[] = '$' . strlen($arg) . "\r\n$arg\r\n";
        }

        $command = implode($cmds);
        $this->setCommand($command);
    }

    public function setCommand($command)
    {
        $this->command = $command;
    }
}