<?php

namespace Routing;

class Request
{
    // The array of cookies.
    protected array $cookies;
    // The array of data passed through a form.
    protected array $data;
    // The array of request headers.
    protected array $headers;
    // The IP address of the user.
    protected string $ip;
    // The HTTP request method.
    protected string $method;
    // The URI.
    protected string $path;
    // The protocol name and version.
    protected string $protocol;
    // Create a new Request instance.
    public function __construct()
    {
        $this->cookies = $_COOKIE;
        $this->data = $_POST;
        $this->headers = apache_request_headers();
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];
    }
    // Get a cookie value by its name.
    public function cookie(string $name): ?string
    {
        if (isset($this->cookies[$name])) {
            return $this->cookies[$name];
        }
        return null;
    }
    // Get a form datum by its name.
    public function data(string $name): ?string
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }
    // Get a header value by its name.
    public function header(string $name): ?string
    {
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }
        return null;
    }
    // Get the IP address.
    public function ip(): string
    {
        return $this->ip;
    }

    // Get the request method.
    public function method(): string
    {
        return $this->method;
    }
    // Get the request URI.
    public function path(): string
    {
        return $this->path;
    }
    // Get the name and version of the protocol.
    public function protocol(): string
    {
        return $this->protocol;
    }

    // Get a value from $_ENV var.
    public function getEnv(string$key): mixed
    {
        return $_ENV[$key] ?? null;
    }

    // Set a value on $_ENV var.
    public function setEnv(string $key, mixed $value): void
    {
        $_ENV[$key] = $value;
    }
}