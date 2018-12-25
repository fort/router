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
        $uri = substr($uri, 0, strpos($uri, '?'));

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
        $pattern = preg_replace_callback('#\/{[a-zA-Z0-9_]+\??}#', function ($matches) {
            if (strpos($matches[0], '?') !== false) {
                return '(\/[0-9a-zA-Z_]+)?';
            }
            return '\/([0-9a-zA-Z]+)';
        }, $this->uri_pattern);

        return '#^' . $pattern . '$#';

    }
    protected function parseUri($uri, $re)
    {
        if (!preg_match($re, $uri, $matches)) {
            return false;
        }
        unset($matches[0]);
        foreach ($matches as &$item) {
            $item = trim($item, '/');
        }
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