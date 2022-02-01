<?php

namespace Routing;

use Closure;

class Router
{
    // The array of registered routes.
    // It's the array of Route objects.
    protected array $routes = [];
    // Dispath the request to the application.
    public function dispatch(): void
    {
        // We first create a request and response instances.
        $request = new Request;
        $response = new Response;
        // We go through all registered routes
        foreach ($this->routes as $route) {
            // And try to find one that matches the current request.
            if ($request->method() == $route->method() && $request->path() == $route->path()) {
                // If it matches, we handle the action.
                $route->handle($request, $response);
                // Finally, we stop the script returning void.
                return;
            }
        }
        // Otherwise, we display 404 error page.
        $response->onError(404);
    }
    // Register a new route with the router.
    public function register(string $method, string $path, Closure $handler): self
    {
        // We simply create a new Route instance and push it to the array of registered routes.
        array_push($this->routes, new Route($method, $path, $handler));
        return $this;
    }
}
