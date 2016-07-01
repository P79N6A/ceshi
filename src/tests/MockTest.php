<?php

class Dog {

    function yeh(){

    }
}
/**
 * 请添加描述
 * Class StackTest
 */
class MockTest extends TestCase {

    /**
     * 请添加描述
     */
    public function testGloble(){

        $m = \Mockery::mock('Dog');
    }

}
