<?php
/**
 * Created by PhpStorm.
 * User: xssy
 * Date: 2019/7/1
 * Time: 9:36 AM
 */

//An example for callback function
function my_callback_function() {
    echo 'hello world!';
}

//An example for callback method
class MyClass {

    static function myCallBackMethod() {
        echo 'Hello world!';
    }
}

//Type1 simple callback
call_user_func('my_callback_function');


//Type2 Static class method call
call_user_func('MyClass','myCallBackMethod');

//Type3 Object method call
$obj = new MyClass();

call_user_func(array($obj,'myCallBackMethod'));

//Type4 Static Class Method call (As of PHP 5.2.3)
call_user_func('MyClass::myCallBackMethod');

//Type5 Relative static Class Method Call (AS of PHP 5.3.0)
class A{
    public static function who() {
        echo "A\n";
    }
}

class B extends A{
    public static function who() {
        echo "B\n";
    }
}

call_user_func(array('B','parent::who'));//A

//Type6 Object implementing __invoke call be used as callable (since PHP 5.3)

Class C{
    public function __invoke($name)
    {
        echo 'Hello',$name,"\n";
    }
}
$c = new C();
call_user_func($c,'PHP!');



