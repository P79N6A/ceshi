<?php
/**
 * 数据库伪造
 */
require 'vendor/autoload.php';

$faker = Faker\Factory::create('zh_CN');
//echo $faker->firstName('cui');
//echo $faker->name;

echo $faker->company;
//echo $faker->uuid;

//echo $faker->randomHtml(2);

//echo $faker->image();