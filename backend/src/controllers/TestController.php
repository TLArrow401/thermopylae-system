<?php

namespace Backend\Controllers;

class TestController
{
  public function index()
  {
    return [
      "success" => true,
      "message" => "Ruta funcionando correctamente ✅"
    ];
  }

  public function store($request, $params)
  {
    // Leer el body JSON enviado
    $inputData = json_decode(file_get_contents("php://input"), true);

    return [
      "success" => true,
      "message" => "Hola desde POST",
      "data" => $inputData
    ];
  }

  public function update($request, $params)
  {
    $inputData = json_decode(file_get_contents("php://input"), true);

    return [
      "success" => true,
      "message" => "Recurso actualizado correctamente",
      "id" => $params['id'] ?? null,
      "updatedData" => $inputData
    ];
  }

  public function show($request, $params)
  {
    $id = $params['id'] ?? null;

    if (!$id) {
      http_response_code(400);
      return [
        "success" => false,
        "message" => "ID inválido o no proporcionado"
      ];
    }

    return [
      "success" => true,
      "message" => "Recurso encontrado",
      "data" => [
        "id" => $id,
        "nombre" => "Elemento de prueba",
        "rol" => "Demo"
      ]
    ];
  }
}
