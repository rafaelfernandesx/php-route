<?php
// If you avoid using Composer,
// place spl_autoload_register instead
require_once __DIR__ . '/../../vendor/autoload.php';

require_once '../src/AuthMiddleware.php';

// First of all, we should create a Router instance.
$router = new Routing\Router;
// The instance is empty. We need to register routes.
// We will register the only one route that responds to '/' URI
// and the 'GET' HTTP request method. It means if the server
// receives the request with the 'GET' method, and the request is sent
// to '/' directory, the router invokes the following action:
$router->register('GET', '/', function ($request, $response) {
    // If a 'been' cookie is not set
    if (!$request->cookie('been')) {
        // Set the 'been' cookie
        $response->cookie("been", true);
        // Set the response body
        $response->body("Hello {$request->ip()}!");
        // Send the response
        $response->send();
    }
    // Otherwise...
    else {
        // Set another response body and send the response.
        $response->body(json_encode(["Welcome back, {$request->ip()}!"]))->send();
    }
});

$router->register('GET', '/hello', function ($request, $response) {
    $response->body("Olá, {$request->ip()}!")->send();
}, AuthMiddleware::handler(...));



$router->notFound(function ($request, $response) {
    $response->body('<h1>418 I\'m a teapot</h1>')->statusCode(418)->send('HTML');
});

// Start the application.
$router->dispatch();
