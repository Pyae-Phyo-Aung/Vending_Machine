<?php
header("Content-Type: application/json");

require_once __DIR__ . '../../config/db_connect.php';
$database = new Database();
$pdo = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? explode('/', trim($_GET['path'], '/')) : [];
$input = json_decode(file_get_contents('php://input'), true);

if (!$path) {
  echo json_encode(["error" => "No endpoint specified"]);
  exit;
}

$resource = $path[0] ?? null;
$id = $path[1] ?? null;

switch ($resource) {
  case 'login':
    require_once __DIR__ . '/login.php';
    break;

  case 'users':
    require_once __DIR__ . '/Auth.php';
    require_once __DIR__ . '/UserAPI.php';
    $userApi = new UserAPI($pdo);
    $userApi->handle($method, $id, $input);
    break;

  case 'products':
    require_once __DIR__ . '/Auth.php';
    Auth::check();
    require_once __DIR__ . '/ProductAPI.php';
    $productApi = new ProductAPI($pdo);
    $productApi->handle($method, $id, $input);
    break;

  default:
    http_response_code(404);
    echo json_encode(["error" => "Resource not found"]);
    break;
}
