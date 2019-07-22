<?php

/**
 * Created by PhpStorm.
 * User: xssy
 * Date: 2019/7/22
 * Time: 11:28 AM
 */
class Test2
{
    private static $logger;

    public static function getLogger()
    {
        return self::$logger ?: self::$logger = self::createLogger();
    }

    private static function createLogger()
    {
        return new Logger();
    }

    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    public function __call($name, $arguments)
    {
        call_user_func([self::getLogger(), $name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        forward_static_call_array([self::getLogger(), $name], $arguments);
    }
}

interface LoggerInterface
{
    function info($message, array $content = []);

    function alter($message, array $content = []);
}

class Logger implements LoggerInterface
{
    function info($message, array $content = [])
    {
        echo 'this is Log method info' . PHP_EOL;
        var_dump($content);
    }

    function alter($message, array $content = [])
    {
        echo 'this is Logger method alter:' . $message . PHP_EOL;
    }
}

Test2::info('喊个口号：',['好好','学习','天天','向上']);
$test = new Test2();
$test->alter('hello');
//this is Log method info
//array(4) {
//  [0]=>
//  string(6) "好好"
//  [1]=>
//  string(6) "学习"
//  [2]=>
//  string(6) "天天"
//  [3]=>
//  string(6) "向上"
//}
//this is Log method alert: hello
