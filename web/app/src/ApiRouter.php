<?php

namespace App;

use Doctrine\ORM\EntityManager;

class ApiRouter
{
    private $request;
    private $path;
    private $method;
    private $em;
    private array $routes;

    public function __construct(EntityManager $em)
    {
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url($this->request, PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->em = $em;
    }

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    public function handleRequest()
    {
        /* @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->matches($this->path, $this->method)) {
                return $route->render($this->path, $this->em);
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        exit();
    }
}