<?php
use Base\Controllers;

class ErrorController extends Controllers
{
    // 推广起始页
    public function errorAction($exception) {
        if  ($exception instanceof \Exception\HttpException) {
            $message = ''.$exception->getMessage();
        } else {
            if ($_SERVER['DEBUG']) {
                $message = ''.$exception->getMessage();
            } else {
                $message = '系统内部错误';
            }
        }
        $http_code = ($exception instanceof Exception\HttpException) ? $exception->getHttpCode() : 500;
        set_status($http_code);
        if (is_ajax() || is_cli()) {
            @header('Content-type: application/json');
            echo json_encode(['message' => $message]);
            exit;
        } else {
           echo $message;
        }
    }
}