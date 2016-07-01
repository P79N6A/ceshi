<?php


class PhakeTest extends TestCase {


    public function testStub() {
        
        $mock = Phake::partialMock('MyClass', 42);

        $this->assertEquals(42, $mock->foo());

    }
}