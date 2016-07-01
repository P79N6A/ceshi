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

        $m = m::mock('MyClass', array(1))->shouldReceive('foo')->andReturn(1)->getMock();

        $this->assertEquals(1, $m->shouldReceive('foo'));
    }

}
