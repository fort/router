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
        $className = $this->router->getController();
        $method = $this->router->getAction();
        $args = $this->router->getArgs();

        if (!class_exists($className)) {
            throw new Exception("controller {$className} not found!");
        }

        $controller = new $className();
        if (!method_exists($controller, $method)) {

            throw new Exception("controller {$className}:{$method} not exists!");
        }

        $reflection = new ReflectionMethod($className, $method);
        $params = $reflection->getParameters();
        if ($params) {
            $firstParam = current($params);
            if ($firstParam->getClass() && $firstParam->getClass()->name == 'Request') {
                array_unshift($args, $this->request);
            }
        }

        return call_user_func_array(array($controller, $method), $args);

    }


}