<?php

namespace RRoute\Routing;

class Response
{
    // The response body.
    protected string $body;
    // Cookie files to set up.
    protected array $cookies = [];
    // The response headers.
    protected array $headers = [];
    // The response status code.
    protected int $statusCode = 200;
    // Send an error response.
    public function onError(int $statusCode): void
    {
        $this->statusCode($statusCode);
        $this->body("Error {$statusCode}");
        $this->send();
    }
    // Send the response.
    public function send(string $type = 'JSON'): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        foreach ($this->cookies as $cookie) {
            header("Set-Cookie: {$cookie}");
        }
        switch ($type) {
            case 'JSON':
                header('Content-Type: application/json');
                break;
            default:
                header('Content-Type: text/html');
                break;
        }
        echo $this->body;
        exit;
    }
    // Set a response body.
    public function body(string $body): self
    {
        $this->body = $body;
        return $this;
    }
    // Set a new cookie file.
    public function cookie(string $name, string $value): self
    {
        $cookie = "{$name}={$value}";
        array_push($this->cookies, $cookie);
        return $this;
    }
    // Set a response header.
    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }
    // Set a response status code.
    public function statusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
