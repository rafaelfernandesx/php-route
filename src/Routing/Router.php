<?php

namespace Routing;

use Closure;

class Router
{
    // The array of registered routes.
    // It's the array of Route objects.
    protected array $routes = [];

    protected array $notFound = [];

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
        //custom 404 page
        if (!empty($this->notFound)) {
            //verifica se tem um middleware para chamar
            if (!empty($this->notFound['middleware'])) {
                call_user_func_array($this->notFound['middleware'], [$request]);
            }
            //chama a função de erro 404
            call_user_func_array($this->notFound['handler'], [$request, $response]);
            exit;
        }

        //route not found default
        $response->onError(404);
    }
    // Register a new route with the router.
    public function register(string $method, string $path, Closure $handler, ?Closure $middleware = null): self
    {
        // We simply create a new Route instance and push it to the array of registered routes.
        array_push($this->routes, new Route($method, $path, $handler, $middleware));
        return $this;
    }

    // Register a notFound route with the router.
    public function notFound(Closure $handler, ?Closure $middleware = null): self
    {
        $this->notFound = ['handler'=>$handler, 'middleware'=>$middleware];
        return $this;
    }
}
