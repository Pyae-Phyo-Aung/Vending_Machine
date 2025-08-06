<?php
require_once __DIR__ . '/../config/db_connect.php';

class UsersController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index()
    {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $password, $role)
    {
        $stmt = $this->conn->prepare("INSERT INTO users (name, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$name, $password, $role]);
    }

    public function update($id, $name, $role)
    {
        $stmt = $this->conn->prepare("UPDATE users SET name=?, role=? WHERE id=?");
        $stmt->execute([$name, $role, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return false;
        } else {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        }
    }
}
