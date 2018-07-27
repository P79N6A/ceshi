<?php
/**
 * @author haicheng
 */

error_reporting(E_ALL);
ini_set('display_errors','Off');

require("vendor/autoload.php");
$swagger = \Swagger\scan('./swagger/');
header('Content-Type: application/json');
echo $swagger;