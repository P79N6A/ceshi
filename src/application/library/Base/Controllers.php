<?php namespace Base;


use Exception\HttpException;
use Yaf_Controller_Abstract;
use LogFile;


abstract class Controllers extends Yaf_Controller_Abstract
{
    public $view = null;
    public $validator = null;

    public function init()
    {
        $this->request = $this->getRequest();
        $this->view = $this->getView();
    }

    public function view($tpl, array $parameter = null)
    {
        $this->view->display($tpl, $parameter);
        exit();
    }

    public function assign($name, $value)
    {
        return $this->view->assign($name, $value);
    }

    public function render($tpl, array $parameter = null)
    {
        return $this->view->render($tpl, $parameter);
    }

    public function echoJson($message, $code = 200)
    {
        header('Content-type: application/json');
        set_status($code);
        if (!is_array($message)) {
            $message = array($message);
        }
        echo json_encode($message);

        exit();
    }

    public function echoJsonResult(array $data)
    {
        $code = $data['code'];
        unset($data['code']);

        if (count($data) == 1 && isset($data['data'])) {

            $this->echoJson($data['data'], $code);
        }
        $this->echoJson($data, $code);
    }
}