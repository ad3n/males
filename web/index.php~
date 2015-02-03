<?php
$loader = require '../vendor/autoload.php';
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ihsanuddin\Event\PreRequestEvent;
use Ihsanuddin\Event\PostRequestEvent;
use Ihsanuddin\Event\PreResponseEvent;

$request = Request::createFromGlobals();

$app = new Ihsanuddin\Application(array('database' => array('host' => 'localhost')));

var_dump($app->getConfig('database'));

$app->route('/hello/{name}', function ($name) {
    return new Response('Hello '.$name);
});

$app->on('pre.request', function (PreRequestEvent $event) {
    if ('/admin' === $event->getRequest()->getPathInfo()) {
        echo 'Access Denied!';
        exit;
    }
});

$app->on('post.request', function (PostRequestEvent $event) {
    if ('/hello/surya' === $event->getRequest()->getPathInfo()) {
        echo 'Post Request Event. <br/>';
    }
});

$app->on('pre.response', function (PreResponseEvent $event) {
    $response = $event->getResponse();

    $response->setStatusCode(500);
});

$response = $app->handle($request);
$response->send();