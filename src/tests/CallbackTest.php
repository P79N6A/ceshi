<?php

/**
 * Class CallbackTest
 */
class CallbackTest extends TestCase {

    /**
     *
     */
    public function testNormalFunction() {

        function fnCallback() {
            echo 'args ----> ' . var_export(func_get_args(), true);
        }

        call_user_func('fnCallback', array(
            1,
            'back' . ""
        ));
    }

    /**
     * 静态函数
     *
     * @AUTHOR haicheng
     */
    public function testStaticFunction() {

        call_user_func(array(
            'MyClass',
            'fnCallback'
        ), array(
            2,
            'static class call'
        ));
    }

    /**
     * 实例化函数
     *
     * @AUTHOR haicheng
     */
    public function testClassFunction() {

        $testClass = new MyClass2();

        call_user_func(array(
            'testClass',
            'fnCallback'
        ), array(
            3,
            'normal class call'
        ));
    }

}

class MyClass {

    public static function fnCallback() {
        echo 'args ----> ' . var_export(func_get_args(), true) . "\n\n\n";
    }
}
class MyClass2 {

    private $_name = 'abc';

    public function fnCallback() {
        echo 'args ----> ' . var_export(func_get_args(), true);
        echo 'name ----> ' . $this->_name;
    }
}