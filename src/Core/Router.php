<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    /** @var array<string, array{path: string, handler: callable|string, method: string}> */
    private array $routes = [];
    
    private mixed $notFoundHandler = null;
    private mixed $methodNotAllowedHandler = null;
    
    public function get(string $path, mixed $handler, ?string $name = null): void
    {
        $this->addRoute('GET', $path, $handler, $name);
    }
    
    public function post(string $path, mixed $handler, ?string $name = null): void
    {
        $this->addRoute('POST', $path, $handler, $name);
    }
    
    private function addRoute(string $method, string $path, mixed $handler, ?string $name = null): void
    {
        $route = [
            'path' => $path,
            'handler' => $handler,
            'method' => $method,
            'pattern' => $this->compilePattern($path),
        ];
        
        $this->routes[] = $route;
        
        if ($name !== null) {
            $this->routes[$name] = $route;
        }
    }
    
    private function compilePattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    public function setNotFoundHandler(mixed $handler): void
    {
        $this->notFoundHandler = $handler;
    }
    
    public function setMethodNotAllowedHandler(mixed $handler): void
    {
        $this->methodNotAllowedHandler = $handler;
    }
    
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        $matchedRoute = null;
        $pathMatched = false;
        $params = [];
        
        foreach ($this->routes as $route) {
            if (!isset($route['pattern'])) {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                $pathMatched = true;
                
                if ($route['method'] === $method) {
                    $matchedRoute = $route;
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }
                    break;
                }
            }
        }
        
        if ($matchedRoute === null && !$pathMatched) {
            $this->handleNotFound();
            return;
        }
        
        if ($matchedRoute === null) {
            $this->handleMethodNotAllowed();
            return;
        }
        
        $this->callHandler($matchedRoute['handler'], $params);
    }
    
    /**
     * @param array<string, mixed> $params
     */
    private function callHandler(mixed $handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func($handler, $params);
            return;
        }
        
        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler);
            $instance = new $class();
            call_user_func([$instance, $method], $params);
            return;
        }
        
        if (is_array($handler) && count($handler) === 2) {
            $instance = new $handler[0]();
            call_user_func([$instance, $handler[1]], $params);
            return;
        }
        
        throw new \RuntimeException('Invalid handler');
    }
    
    private function handleNotFound(): void
    {
        http_response_code(404);
        if ($this->notFoundHandler !== null) {
            $this->callHandler($this->notFoundHandler, []);
        } else {
            echo '404 Not Found';
        }
    }
    
    private function handleMethodNotAllowed(): void
    {
        http_response_code(405);
        if ($this->methodNotAllowedHandler !== null) {
            $this->callHandler($this->methodNotAllowedHandler, []);
        } else {
            echo '405 Method Not Allowed';
        }
    }
}
