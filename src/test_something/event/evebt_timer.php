<?php
$base      = new EventBase();
$n         = 1;
$last_time = microtime(true);
$e         = new Event($base, -1, Event::TIMEOUT | Event::PERSIST, function ($fd, $what, $n) use (&$e, &$last_time) {
    $current_time = microtime(true);
    $elapsed_time = $current_time - $last_time;
    echo "$elapsed_time seconds elapsed\n";
    $last_time = $current_time;
    $e->delTimer();   // Trigger Once
}, $n);
$e->add($n);
$base->loop();

echo 'everything is ok';