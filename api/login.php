<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;

$secret_key = "123456789";

$database = new Database();
$pdo = $database->getConnection();

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

if (empty($input['name']) || empty($input['password'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing username or password']);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
$stmt->execute([$input['name']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($input['password'], $user['password'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid credentials']);
  exit;
}

$issuedAt = time();
$expire = $issuedAt + 3600;

$payload = [
  'iat' => $issuedAt,
  'exp' => $expire,
  'sub' => $user['id'],
  'role' => $user['role']
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

echo json_encode([
  'message' => 'Login successful',
  'token' => $jwt
]);
