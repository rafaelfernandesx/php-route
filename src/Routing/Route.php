<?php

namespace Routing;

use Closure;

class Route
{
    // The route action.
    // If the route matches the HTTP request,
    // this action will be invoked.
    protected Closure $handler;
    // The HTTP method the route responds to.
    protected string $method;
    // The URI the route responds to.
    protected string $path;
    // middleware to exectute before handle.
    protected ?Closure $middleware;
    // Create a new Route instance.
    public function __construct(string $method, string $path, Closure $handler, ?Closure $middleware)
    {
        $this->handler = $handler;
        $this->method = $method;
        $this->path = $path;
        $this->middleware = $middleware;
    }
    // Run the route action.
    public function handle(Request $request, Response $response): void
    {
        if ($this->middleware) {
            call_user_func_array($this->middleware, [$request]);
        }
        call_user_func_array($this->handler, [$request, $response]);
    }
    // Get the HTTP method the route responds to.
    public function method(): string
    {
        return $this->method;
    }
    // Get the URI the route responds to.
    public function path(): string
    {
        return $this->path;
    }
}