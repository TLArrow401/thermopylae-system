<?php

namespace Backend\Utils;

class Router
{
  private array $routes = [];

  public function add(string $method, string $path, $handler, array $middleware = []) {
    $path = rtrim($path, '/');
    if ($path === '') $path = '/';
    $this->routes[] = [
      'method' => strtoupper($method),
      'path' => $path,
      'handler' => $handler,
      'middleware' => $middleware
    ];
  }

  public function resolve() {
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    if ($uri === '') $uri = '/';

    foreach ($this->routes as $route) {
      if ($route['method'] !== $requestMethod) continue;

      $params = [];
      $pattern = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $route['path']);
      $pattern = '#^' . $pattern . '$#';

      if (preg_match($pattern, $uri, $matches)) {
        foreach ($matches as $k => $v) {
          if (is_string($k)) $params[$k] = $v;
        }

        // Ejecutar middlewares en orden
        foreach ($route['middleware'] as $mw) {
          if (is_callable($mw)) {
            $continue = call_user_func($mw, $_REQUEST, $params);
            if ($continue === false) return;
          } elseif (is_array($mw) && is_callable($mw)) {
            $continue = call_user_func($mw, $_REQUEST, $params);
            if ($continue === false) return;
          }
        }

        // Llamar handler
        if (is_callable($route['handler'])) {
          return call_user_func($route['handler'], $_REQUEST, $params);
        } elseif (is_array($route['handler']) && method_exists($route['handler'][0], $route['handler'][1])) {
          return call_user_func($route['handler'], $_REQUEST, $params);
        } else {
          http_response_code(500);
          echo json_encode(['error' => 'Handler inválido']);
          return;
        }
      }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
  }
}
