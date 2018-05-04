<?php
$_POST['name'] = null;
if(isset($_POST['name'])) {
    echo '';
} else {
    echo 'nothing';
}

//for ($c = 'a'; $c <= 'z'; $c++) {
//    echo $c . "\n";
//}

for($i = ord('a'); $i < ord('z'); $i++) {
    echo chr($i) . "\n";
}

echo ord('a');