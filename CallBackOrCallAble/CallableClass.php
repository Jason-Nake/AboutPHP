<?php
/**
 * Created by PhpStorm.
 * User: xssy
 * Date: 2019/7/1
 * Time: 9:58 AM
 */

namespace phpabout\CallBackOrCallAble;


//当尝试以调用函数的方式调用一个对象时，__invoke该方法会被自动调用
class CallableClass
{
    public function __invoke($param1,$param2)
    {
        var_dump($param1,$param2);
    }
}

$obj = new CallableClass;

$obj(123,456);


//实例化对象本身是不能被调用，但是类中如果实现 __invoke() 方法，则把实例对象当作方法调用，会自动调用到 __invoke() 方法，参数顺序相同。
var_dump(is_callable($obj));//true;
