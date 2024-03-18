<?php

namespace App;

use Doctrine\ORM\EntityManager;
use ReflectionMethod;

class Route
{
    private string $pattern;
    private $view;
    private string $method;

    public function __construct(string $pattern, callable $view, string $method = "GET")
    {
        $this->pattern = $pattern;
        $this->view = $view;
        $this->method = $method;
    }

    public function matches(string $path, string $method): bool
    {
        return preg_match($this->pattern, $path) && $this->method == $method;
    }

    public function render($path, EntityManager $em)
    {
        $matches = [];
        preg_match($this->pattern, $path, $matches);
        $class_name = $this->view[0];
        $method_name = $this->view[1];
        $reflection_method = new ReflectionMethod($class_name, $method_name);

        $argument_names = [];
        foreach ($reflection_method->getParameters() as $parameter) {
            $argument_names[] = $parameter->getName();
        }

        $views_arguments = array_map(function ($arg) use ($matches, $em) {
            if ($arg == "em") {
                return $em;
            }
            return $matches[$arg];
        }, $argument_names);

        return call_user_func_array([$class_name, $method_name], $views_arguments);
    }
}