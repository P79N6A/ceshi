<?php

class StackTest extends TestCase {

    public function testGloble(){
        $this->assertEquals('user' , $GLOBALS['DB_USER']);
    }

//        public function testEmpty() {
//            $stack = array();
//            $this->assertEmpty($stack);
//
//            return $stack;
//        }
//
//        /**
//         * @depends testEmpty
//         */
//        public function testPush($stack) {
//            array_push($stack, 'foo');
//            $this->assertNotEmpty($stack);
//
//            return $stack;
//        }
//
//    public static function setUpBeforeClass()
//    {
//        fwrite(STDOUT, __METHOD__ . "\n");
//    }
//
//    /**
//     * @dataProvider additionProvider
//     */
//    protected function setUp()
//    {
//        fwrite(STDOUT, __METHOD__ . "\n");
//    }
//
//    /**
//     * @dataProvider additionProvider
//     */
//    public function testAdd($a, $b, $expected)
//    {
//        //$this->assertEquals($expected, $a + $b);
//    }
//
//    public function additionProvider()
//    {
//        return array(
//            array(0, 0, 0),
//            array(0, 1, 1),
//            array(1, 0, 1),
//            array(1, 1, 3)
//        );
//    }

//    /**
//     * @dataProvider provider
//     */
//    public function testMethod($data) {
//        $this->assertTrue($data);
//    }
//
//    public function provider() {
//        return array(
//            'my named data' => array(true),
//            'my data'       => array(true)
//        );
//    }
}
