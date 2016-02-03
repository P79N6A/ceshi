<?php
require '../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$filename = 'config.yaml';
$array = Yaml::parse(file_get_contents($filename));

print_r($array);
print Yaml::dump($array);