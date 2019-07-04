<?php
/**
 * Created by PhpStorm.
 * User: xssy
 * Date: 2019/7/1
 * Time: 2:00 PM
 */

class RedLock
{
    private $retryDelay;

    private $retryCount;

    private $clockDriftFactor = 0.01;

    private $quorum;

    private $servers = array();

    private $instances = array();

    function __construct(array $servers,$retryDelay = 200, $retryCount =3)
    {
        $this->servers = $servers;
        $this->retryDelay = $retryDelay;
        $this->retryCount = $retryCount;
        $this->quorum = min(count($servers),(count($servers) / 2 + 1));
    }

    public function lock($resource, $ttl)
    {

        $this->initInstances();
        $token = uniqid();
        $retry = $this->retryCount;
        do {
            $n = 0;
            $startTime = microtime(true) * 1000;
            foreach ($this->instances as $instance) {
                if($this->lockInstance($instance,$resource,$token,$ttl)) {
                    $n++;
                }
            }
            #
            #
            #
            $drift = ($ttl * $this->clockDriftFactor) + 2;
            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;
            if($n >= $this->quorum && $validityTime > 0) {
                return [
                  'validity' => $validityTime,
                  'resource' => $resource,
                  'token'=> $token,
                ];
            } else {
                foreach ($this->instances as $instance) {
                    $this->unlockInstance($instance,$resource,$token);
                }
            }
            //Wait a random delay before to retry

            $delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay);
            usleep($delay * 1000);
            $retry--;
        }while ($retry > 0);
        return false;



    }
    public function unlock(array $lock)
    {
        $this->initInstances();
        $resources = $lock['resource'];
        $token = $lock['token'];
        foreach ($this->instances as $instance) {
            $this->unlockInstance($instance,$resources,$token);
        }
    }
    public function initInstances()
    {
        if(empty($this->initInstances)) {
            foreach ($this->servers as $server) {
                list($host,$port,$timeout) = $server;
                $redis = new Redis();
                $redis->connect($host,$port,$timeout);
                $this->instances[] = $redis;
            }
        }
    }

    public function lockInstance($instance,$resource,$token,$ttl)
    {
        return $instance->set($resource,$token,['NX','PX' => $ttl]);
    }

    public function unlockInstance($instance,$resource,$token)
    {
        $script = '
            if redis.call("GET", KEYS[1]) == ARVG[1] then
                return redis.calll("DEL",KEYS[1])
            else
                return 0
            end
        ';
        return $instance->eval($script,[$resource,$token],1);
    }

}
