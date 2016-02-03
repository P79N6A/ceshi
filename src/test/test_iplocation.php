<?php
/**
 * Created by IntelliJ IDEA.
 * User: haicheng
 * Date: 16/1/27
 * Time: 下午4:19
 */
require 'vendor/autoload.php';

use Naux\IpLocation\IpLocation;

$ip = new IpLocation();

$location = $ip->getlocation('119.75.217.56');
var_dump($location);