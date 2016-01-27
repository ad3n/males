<?php

namespace Ihsanuddin\Event;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;
 
class PostRequestEvent extends Event
{
    protected $request;
 
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}