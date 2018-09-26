<?php
/**
 * 数据库伪造
 */
require '../vendor/autoload.php';

$faker = Faker\Factory::create('zh_CN');
echo $faker->firstName('cui');
for ($i = 0; $i < 20; $i++) {
	echo $faker->name, "\n";
}

//echo $faker->company;
//echo $faker->uuid;

//echo $faker->randomHtml(2);

//echo $faker->image();