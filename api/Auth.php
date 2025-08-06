<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
  private static $secret_key = "123456789";

  public static function check()
  {
    if (function_exists('getallheaders')) {
      $headers = getallheaders();
    } else {
      $headers = [];
      foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
          $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
          $headers[$key] = $value;
        }
      }
    }

    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(['error' => 'Authorization header missing']);
      exit;
    }

    if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
      http_response_code(401);
      echo json_encode(['error' => 'Malformed Authorization header']);
      exit;
    }

    $token = $matches[1];

    try {
      $decoded = JWT::decode($token, new Key(self::$secret_key, 'HS256'));
      return $decoded;
    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(['error' => 'Invalid token']);
      exit;
    }
  }
}
