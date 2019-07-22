<?php
/**
 * Created by PhpStorm.
 * User: xssy
 * Date: 2019/7/22
 * Time: 11:08 AM
 */

class TestCallStatic
{
    public function __call($name, $arguments)
    {
        echo 'this is __call:' . PHP_EOL;
    }

    public static function __callStatic($name,$arguments)
    {
        echo 'this is __callStatic:' . PHP_EOL;
    }
}

$test = new TestCallStatic();
$test->hello();
$test::hi();
//this is __call:hello
//this is __callStatic:hi
