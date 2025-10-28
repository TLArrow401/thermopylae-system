<?php
  namespace Backend;
  use Backend\Utils\Router;
  use Backend\Controllers\TestController;
  $routes = new Router();

  $routes->add("GET", "/test", [new TestController(), "index"]);
  $routes->add("GET", "/test/:id", [new TestController(), "show"]);
  $routes->add("POST", "/test", [new TestController(), "store"]);
  $routes->add("PUT", "/test/:id", [new TestController(), "update"]);






  return $routes;
?>