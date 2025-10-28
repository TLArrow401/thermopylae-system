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
    // Obtiene la URI de la solicitud
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    if ($uri === '') {
        $uri = '/';
    }

    // Elimina el subdirectorio del inicio de la URI
    $basePath = '/Project/backend'; // Reemplaza por el subdirectorio de tu proyecto
    if (str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }


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
          $response = call_user_func($route['handler'], $_REQUEST, $params);
          echo json_encode($response);
          return;
        } elseif (is_array($route['handler']) && method_exists($route['handler'][0], $route['handler'][1])) {
          $response = call_user_func($route['handler'], $_REQUEST, $params);
          echo json_encode($response);
          return;
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
