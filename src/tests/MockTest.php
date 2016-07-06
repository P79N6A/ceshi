<?php

use \Mockery as m;

/**
 * 请添加描述
 * Class StackTest
 */
class MockTest extends TestCase {

    /**
     * 请添加描述
     */
    public function testGloble(){

        $m = $this->getMockBuilder(MyClass::class)->setMethods(['foo'])->getMock();

        $this->assertEquals(1, 1);
    }

}
