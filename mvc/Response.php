<?php

class Response
{
    protected $body;
    protected $statusCode = 200;

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    public function __construct()
    {

    }
}