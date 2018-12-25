<?php


class Dispatcher
{
    protected $request;
    protected $router;

    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    public function processRequest()
    {
        if (is_callable($this->router->getCallback())) {
            return $this->processCallback();
        } else {
            return $this->processObject();
        }
    }

    protected function processCallback()
    {
        return call_user_func_array($this->router->getCallback(), $this->router->getArgs());
    }

    protected function processObject()
    {
        //Users@list
        $controller = $this->router->getController();
        $method = $this->router->getAction();
        $args = $this->router->getArgs();
        $args[] = 'price';

        if (!class_exists($controller)) {
            throw new Exception("controller {$controller} not found!");
        }

        $controllerObj = new $controller();
        if (!method_exists($controllerObj, $method)) {

            throw new Exception("controller {$controller}:{$method} not exists!");
        }

        return call_user_func_array(array($controllerObj, $method), $args);

    }


}