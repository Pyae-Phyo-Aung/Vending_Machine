<?php
class ProductAPI
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function handle($method, $id, $input)
  {
    switch ($method) {
      case 'GET':
        if ($id) $this->getProduct($id);
        else $this->getProducts();
        break;
      case 'POST':
        $this->createProduct($input);
        break;
      case 'PUT':
        if ($id) $this->updateProduct($id, $input);
        else $this->error("Product ID required");
        break;
      case 'DELETE':
        if ($id) $this->deleteProduct($id);
        else $this->error("Product ID required");
        break;
      default:
        http_response_code(405);
        $this->error("Method not allowed");
    }
  }

  private function getProducts()
  {
    $stmt = $this->pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
  }

  private function getProduct($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) echo json_encode($product);
    else $this->error("Product not found", 404);
  }

  private function createProduct($data)
  {
    if (empty($data['name']) || !isset($data['price']) || !isset($data['quantity_available'])) {
      $this->error("Missing required fields");
      return;
    }

    $stmt = $this->pdo->prepare("INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)");
    try {
      $stmt->execute([$data['name'], $data['price'], $data['quantity_available']]);
      http_response_code(201);
      echo json_encode(["message" => "Product created", "id" => $this->pdo->lastInsertId()]);
    } catch (PDOException $e) {
      $this->error("Error creating product: " . $e->getMessage());
    }
  }

  private function updateProduct($id, $data)
  {
    $fields = [];
    $params = [];

    if (isset($data['name'])) {
      $fields[] = "name = ?";
      $params[] = $data['name'];
    }
    if (isset($data['price'])) {
      $fields[] = "price = ?";
      $params[] = $data['price'];
    }
    if (isset($data['quantity_available'])) {
      $fields[] = "quantity_available = ?";
      $params[] = $data['quantity_available'];
    }
    if (empty($fields)) {
      $this->error("No fields to update");
      return;
    }
    $params[] = $id;

    $sql = "UPDATE products SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    try {
      $stmt->execute($params);
      echo json_encode(["message" => "Product updated"]);
    } catch (PDOException $e) {
      $this->error("Error updating product: " . $e->getMessage());
    }
  }

  private function deleteProduct($id)
  {
    $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
    try {
      $stmt->execute([$id]);
      echo json_encode(["message" => "Product deleted"]);
    } catch (PDOException $e) {
      $this->error("Error deleting product: " . $e->getMessage());
    }
  }

  private function error($message, $code = 400)
  {
    http_response_code($code);
    echo json_encode(["error" => $message]);
  }
}
