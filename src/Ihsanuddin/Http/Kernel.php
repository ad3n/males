<?php

namespace Ihsanuddin\Http;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Ihsanuddin\Event\PreRequestEvent;
use Ihsanuddin\Event\PostRequestEvent;
use Ihsanuddin\Event\PreResponseEvent;
use Ihsanuddin\Event\PostResponseEvent;
 
class Kernel implements HttpKernelInterface
{
    const PRE_REQUEST = 'pre.request';
    
    const POST_REQUEST = 'post.request';

    const PRE_RESPONSE = 'pre.response';

    protected $routes;
 
    public function __construct()
    {
        $this->routes = new RouteCollection();
        $this->dispatcher = new EventDispatcher();
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $preRequestEvent = new PreRequestEvent();
        $preRequestEvent->setRequest($request);
        $this->dispatcher->dispatch(self::PRE_REQUEST, $preRequestEvent);

        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);
        
        try {
            $attributes = $matcher->match($request->getPathInfo());

            $controller = $attributes['controller'];
            unset($attributes['controller']);

            $postRequestEvent = new PostRequestEvent();
            $postRequestEvent->setRequest($request);
            $this->dispatcher->dispatch(self::POST_REQUEST, $postRequestEvent);

            $response = call_user_func_array($controller, $attributes);

            $preResponseEvent = new PreResponseEvent();
            $preResponseEvent->setResponse($response);
            $this->dispatcher->dispatch(self::PRE_RESPONSE, $preResponseEvent);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not found!', Response::HTTP_NOT_FOUND);
        }
         
        return $response;
    }
 
    public function route($path, $controller) 
    {
        if (! is_callable($controller)) {

            throw new InvalidArgumentException(sprintf('%s is not callable.'));
        }

        $this->routes->add($path, new Route(
            $path,
            array('controller' => $controller)
        ));
    }

 
    public function on($event, $callback)
    {
        if (! is_callable($callback)) {

            throw new InvalidArgumentException(sprintf('%s is not callable.'));
        }

        $this->dispatcher->addListener($event, $callback);
    }

    public function fire($event)
    {
        return $this->dispatcher->dispatch($event);
    }
}