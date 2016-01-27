<?php

namespace Ihsanuddin\Event;
 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;
 
class PreResponseEvent extends Event
{
    protected $response;
 
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
 
    public function getResponse()
    {
        return $this->response;
    }
}