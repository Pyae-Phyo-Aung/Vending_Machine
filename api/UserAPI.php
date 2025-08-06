<?php
require_once 'Auth.php';
class UserAPI
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function handle($method, $id, $input)
  {
    $user = Auth::check();
    switch ($method) {
      case 'GET':
        if ($id) $this->getUser($id);
        else $this->getUsers();
        break;
      case 'POST':
        $this->createUser($input);
        break;
      case 'PUT':
        if ($id) $this->updateUser($id, $input);
        else $this->error("User ID required");
        break;
      case 'DELETE':
        if ($id) $this->deleteUser($id);
        else $this->error("User ID required");
        break;
      default:
        http_response_code(405);
        $this->error("Method not allowed");
    }
  }

  private function getUsers()
  {
    $stmt = $this->pdo->query("SELECT id, name, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
  }

  private function getUser($id)
  {
    $stmt = $this->pdo->prepare("SELECT id, name, role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) echo json_encode($user);
    else $this->error("User not found", 404);
  }

  private function createUser($data)
  {
    if (empty($data['name']) || empty($data['password']) || empty($data['role'])) {
      $this->error("Missing required fields");
      return;
    }
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $this->pdo->prepare("INSERT INTO users (name, password, role) VALUES (?, ?, ?)");
    try {
      $stmt->execute([$data['name'], $passwordHash, $data['role']]);
      http_response_code(201);
      echo json_encode(["message" => "User created", "id" => $this->pdo->lastInsertId()]);
    } catch (PDOException $e) {
      $this->error("Error creating user: " . $e->getMessage());
    }
  }

  private function updateUser($id, $data)
  {
    $fields = [];
    $params = [];

    if (isset($data['name'])) {
      $fields[] = "name = ?";
      $params[] = $data['name'];
    }
    if (isset($data['password'])) {
      $fields[] = "password = ?";
      $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    if (isset($data['role'])) {
      $fields[] = "role = ?";
      $params[] = $data['role'];
    }
    if (empty($fields)) {
      $this->error("No fields to update");
      return;
    }
    $params[] = $id;

    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    try {
      $stmt->execute($params);
      echo json_encode(["message" => "User updated"]);
    } catch (PDOException $e) {
      $this->error("Error updating user: " . $e->getMessage());
    }
  }

  private function deleteUser($id)
  {
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
    try {
      $stmt->execute([$id]);
      echo json_encode(["message" => "User deleted"]);
    } catch (PDOException $e) {
      $this->error("Error deleting user: " . $e->getMessage());
    }
  }

  private function error($message, $code = 400)
  {
    http_response_code($code);
    echo json_encode(["error" => $message]);
  }
}
