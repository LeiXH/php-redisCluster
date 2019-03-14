<?php
/**
 * Created by PhpStorm.
 * User: xhlei
 * Date: 2019/2/27
 * Time: 2:17 PM
 */
namespace redis;

class SessionMannager
{
    private $redis;
    private $expireTime;
    public function __construct($config)
    {
        $this->redis = new RedisClient($config);
        $this->expireTime = 180;
        session_set_save_handler(array($this, "open"), array($this, "close"), array($this, "read"), array($this, "write"), array($this, "destroy"), array($this, "gc"));
        register_shutdown_function(array($this, "test"));
    }

    function open($save_path, $session_name)
    {
        echo __FUNCTION__."\n";
        return true;
    }
    function close()
    {
        echo __FUNCTION__."\n";

        return(true);
    }

    /**
     * @param $id
     * @return bool|string
     */
    function read($id)
    {
        echo __FUNCTION__."\n";
        $val = $this->redis->get($id);
        if ($val) {
            return $val;
        } else {
            return "";
        }
    }
    function write($id, $sess_data)
    {
        echo __FUNCTION__."\n";
        if ($this->redis->set($id, $sess_data)) {
            $this->redis->expire($id, $this->expireTime);
            return true;
        }
        return false;
    }

    function destroy($id)
    {
        echo __FUNCTION__."\n";
        if ($this->redis->delete($id)){
            return true;
        }
        return false;
    }
    function gc($maxlifetime)
    {
        echo __FUNCTION__."\n";
        return true;
    }

    public function __destruct(){
        session_write_close();
    }
    function test()
    {

        echo __FUNCTION__."\n";
    }

}

