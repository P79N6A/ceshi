<?php
require_once __DIR__ . "/TestCase.php";


class ExampleTest extends TestCase {
    public function testExample() {
        $this->assertJson('[]', '这不是一个json');
    }

    public function testExample2() {
        $this->assertJson('[]', '这不是一个json');
    }

}
