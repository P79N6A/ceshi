<?php namespace Base;


abstract class ResourceControllers extends Controllers
{
    public function init()
    {
        parent::init();
    }

    public function __call($method, $parameters)
    {
        var_dump($method);
        echo "ccccc";
        call_user_func_array($method, $parameters);
    }

    public function indexAction()
    {
        call_user_func_array([$this, 'index'], []);
    }

    public function createAction()
    {
        call_user_func_array([$this, 'create'], []);
    }

    public function storeAction()
    {
        call_user_func_array([$this, 'store'], []);
    }

    public function showAction()
    {
        $id = Request::input('action_id', Request::input('id'));
        Request::setParam('id', $id);
        call_user_func_array([$this, 'show'], ['id' => $id]);
    }

    public function editAction()
    {
        $id = Request::input('action_id', Request::input('id'));
        Request::setParam('id', $id);
        call_user_func_array([$this, 'edit'], ['id' => $id]);
    }

    public function updateAction()
    {
        $id = Request::input('action_id', Request::input('id'));
        Request::setParam('id', $id);
        call_user_func_array([$this, 'update'], ['id' => $id]);
    }

    public function deleteAction()
    {
        $id = Request::input('action_id', Request::input('id'));
        Request::setParam('id', $id);
        call_user_func_array([$this, 'delete'], ['id' => $id]);
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