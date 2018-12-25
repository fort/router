<?php

class Router
{
    protected $uri_pattern;
    protected $callback;
    protected $args = [];
    protected $controller;
    protected $action;

    public function __construct($uri_pattern, $callback)
    {
        $this->setUriPattern($uri_pattern);
        $this->callback = $callback;
        $this->parseCallback();
    }

    /**
     * @return mixed
     */
    public function getUriPattern()
    {
        return $this->uri_pattern;
    }

    /**
     * @param mixed $uri_pattern
     */
    public function setUriPattern($uri_pattern)
    {
        $this->uri_pattern = trim($uri_pattern, '/');
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }


    public function match($uri)
    {
        $uri = trim($uri, '/');
        if (strpos($this->uri_pattern, '{') !== false) {
            $re = $this->buildRegExp($uri);
            return $this->parseUri($uri, $re);
        } else {
            return $this->uri_pattern === $uri;
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    protected function buildRegExp()
    {
        $replace_from = '({[a-zA-Z0-9]+})';
        $replace_to = '([0-9a-zA-Z]+)';
        $pattern = '#^' . str_replace('/', '\/', $this->uri_pattern) . '$#';
        $re = preg_replace($replace_from, $replace_to, $pattern);
        return $re;

    }
    protected function parseUri($uri, $re)
    {
        if (!preg_match($re, $uri, $matches)) {
            return false;
        }
        unset($matches[0]);
        $this->args = $matches;
        return true;
    }

    protected function parseCallback()
    {
        $delimiter = '@';

        if (is_string($this->callback)
            && strlen($this->callback)
            && strpos($this->callback, $delimiter) !== false
        ) {

            list($controller, $action) = explode('@', $this->callback);
            if (!$action) {
                throw new Exception("{$this->uri_pattern} is not correct!");
            }

            $this->controller = $controller;
            $this->action = $action;

        } elseif (!is_callable($this->callback)) {
            throw new Exception("{$this->uri_pattern} is not correct!");
        }
    }
}