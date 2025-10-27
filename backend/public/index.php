<?php
  require __DIR__ . '/../vendor/autoload.php';

  use Dotenv\Dotenv;
  use Backend\Models\Database;

  /* Autoload clases */
  $Dotenv = Dotenv::createImmutable(__DIR__ . "/..");
  $Dotenv->load();

  /* Prueba server funciona */
  echo "Servidor funcionando";

  //Header de seguridad basicos
  header("X-Content-Type-Options: nosniff");
  header("X-Frame-Options: DENY");
  //Configuracion de cors
  header("Access-Control-Allow-Origin: *");
  header("Access-control-Allow-Headers: Content-type, Authorization");
  header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Credentials: true");

  //Manejar protocolo preflight requests (OPTIONS)
  if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
  }

  /* Constantes */
  define("ROOT_PATH", dirname(__DIR__) . "/");
  define("APP_PATH", ROOT_PATH . "/src");
  define("CONFIG_PATH", ROOT_PATH . "/config");
  
  //AutoCargador de clases
/*   spl_autoload_register(function($class){
    $classPath = APP_PATH . "/" . str_replace("\\", "/", $class);
    $filePath = ROOT_PATH . "src" . $classPath . ".php";
    if(file_exists($filePath)){
      require_once $filePath;
    }
  }); */

  //Auto cargado de configuraciones
/*   function loadConfig($file){
    $configPath = CONFIG_PATH . "/" . $file . ".php";
    return file_exists($configPath) ? require $configPath : [];
  } */

  // Cargar configuraciones globales
/*   $dbConfig = loadConfig("db"); */

  //Inicializacion de la base de datos
  try {
    $db = Database::getConnection();
    /* echo json_encode(["db" => "connected"]); */
  } catch (\Throwable $th) {
    // Establecer el encabezado de tipo de contenido
    header('Content-Type: application/json');
    // Enviar el código de estado HTTP 500
    http_response_code(500);
    // Devolver una respuesta JSON con un mensaje de error
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor. No se pudo conectar a la base de datos."
    ]);
    // Detener la ejecución del script
    exit();
  }
  


?>