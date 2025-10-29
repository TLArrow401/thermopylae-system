<?php

namespace Backend\Controllers;

use Backend\Models\TestModel;
use Backend\Utils\Response;

class TestController {

  private TestModel $model;

  public function __construct() {
    $this->model = new TestModel();
  }

  // Listar
  public function index()
  {
    $tester = $this->model->getAll();
    Response::json(200, $tester);
    
  }

  // Crear
  public function testCreate($request, $params)
  {
    // Leer el body JSON enviado
    $testData = json_decode(file_get_contents("php://input"), true) ?: [];
   
    /* Logica de validaciones */

    $createData = $this->model->create($testData);
    Response::json(201, $createData);
  }

  // Actualizar
  public function testUpdate($request, $params)
  {
    $id = (int)($params['id'] ?? 0);
    if ($id <= 0) return Response::json(400, 'Id invalido');
    $testData = json_decode(file_get_contents('php://input'), true);
    $updated = $this->model->update($id, $testData);
    if (!$updated) return Response::error(404, 'Test no encontrado');
    Response::json(200, $updated);
  }

  // Buscar por id
  public function showById($request, $params)
  {
    $id = (int)($params['id'] ?? 0);
    if ($id <= 0) return Response::error(404, 'Id invalida');
    $test = $this->model->find($id);
    if (!$test) return Response::error(404, 'Test no encontrado');
    Response::json(200, $test);
  }
}
