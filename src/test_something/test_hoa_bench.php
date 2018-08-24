<?php
require_once 'vendor/autoload.php';

$bench = new Hoa\Bench\Bench();

// Start two marks: “one” and “two”.
$bench->one->start();
$bench->two->start();

usleep(50000);

// Stop the mark “two” and start the mark “three”.
$bench->two->stop();
$bench->three->start();

usleep(25000);

// Stop all marks.
$bench->three->stop();
$bench->one->stop();

// Print statistics.
echo $bench;