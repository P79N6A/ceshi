<?php
require_once __DIR__ . "/TestCase.php";

class StrlengthTest extends TestCase {


    public function testLength() {
        $str = '#戴对墨镜你就红#武大女神黄灿灿，明明可以靠脸吃饭，却是高智商的学霸，更是乐于自黑的逗逼。作为女神学霸，出行旅游，绝对少不了近视墨镜。不仅度数个人定制，款式更是瞬间秒杀全场！拥有一副黄灿灿同款墨镜，不红都难。从网红到时尚icon，就差一副近视墨镜，戴对墨镜，你就红！';

        $len = $this->get_chinese_string_length($str);
        $this->assertEquals(134, $len);
    }

    private function get_chinese_string_length($string) {
        $string = trim($string);
        if ('' === $string) {
            return 0;
        }
        $string_length         = mb_strlen($string, 'UTF-8');
        $chinese_string_length = mb_strlen(preg_replace('/[0-9a-z\s]+/is', '', $string), 'UTF-8');
        if ($string_length === $chinese_string_length) {
            return $string_length;
        }

        return $chinese_string_length + ceil(($string_length - $chinese_string_length) / 2);
    }

}
