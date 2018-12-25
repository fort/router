<?php


class Request
{
    protected $uri;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

}