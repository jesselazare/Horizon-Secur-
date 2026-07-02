<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<string, array{handler: callable|array{class-string, string}, middleware: list<class-string>}>> */
    private array $routes = [];

    /** @var list<class-string> */
    private array $middlewareStack = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    /**
     * @param array{middleware?: list<class-string>} $options
     */
    public function group(array $options, callable $callback): void
    {
        $previous = $this->middlewareStack;
        $this->middlewareStack = array_merge($previous, $options['middleware'] ?? []);

        $callback($this);

        $this->middlewareStack = $previous;
    }

    public function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][$this->normalizePath($path)] = [
            'handler' => $handler,
            'middleware' => $this->middlewareStack,
        ];
    }

    public function dispatch(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path = $this->normalizePath(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

        $route = $this->routes[$method][$path] ?? null;

        if ($route === null) {
            Response::json(['message' => 'Route introuvable.'], 404);

            return;
        }

        $this->runPipeline($route['middleware'], fn () => $this->invokeHandler($route['handler']));
    }

    /**
     * @param list<class-string> $middleware
     */
    private function runPipeline(array $middleware, callable $destination): void
    {
        $pipeline = $destination;

        foreach (array_reverse($middleware) as $middlewareClass) {
            $next = $pipeline;
            $pipeline = static function () use ($middlewareClass, $next): void {
                (new $middlewareClass())->handle($next);
            };
        }

        $pipeline();
    }

    private function invokeHandler(callable|array $handler): void
    {
        if (is_array($handler)) {
            [$class, $action] = $handler;
            $controller = new $class();

            if (!method_exists($controller, $action)) {
                Response::json(['message' => 'Action introuvable.'], 500);

                return;
            }

            $controller->{$action}();

            return;
        }

        $handler();
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
