<?php
  namespace Backend;
  use Backend\Utils\Router;
  use Backend\Controllers\TestController;
  $routes = new Router();

  $routes->add("GET", "/test", [new TestController(), "index"]);
  $routes->add("GET", "/test/:id", [new TestController(), "showById"]);
  $routes->add("POST", "/test", [new TestController(), "testCreate"]);
  $routes->add("PUT", "/test/:id", [new TestController(), "testUpdate"]);






  return $routes;
?>