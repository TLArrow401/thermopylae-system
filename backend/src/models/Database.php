<?php
  namespace Backend\Models;

  use PDO;
  use PDOException;

  class Database {
    private static $conn = null;

    private function __construct(){
      if (self::$conn === null) {
        self::getConnect();
      }
    }

    /* Metodo privado generador de la conexion */
    private static function getConnect(){
      try {
        self::$conn = new PDO(
          "pgsql:host=" . $_ENV["DB_HOST"] . 
          ";port=" . $_ENV["DB_PORT"] .
          ";dbname=" . $_ENV["DB_NAME"],
          $_ENV["DB_USER"],
          $_ENV["DB_PASS"]
        );

        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return self::$conn;
      } catch (PDOException $e) {
        if ($_ENV["APP_DEV"] === "development") {
          die("Error de Base de Datos (Dev Mode): " . $e->getMessage());
        }
        die("⚠ Error al conectar con la base de datos");
      }
    }

    public static function getConnection() {
      if (self::$conn === null) {
        self::getConnect();
      }
      return self::$conn;
    }
  }
?>