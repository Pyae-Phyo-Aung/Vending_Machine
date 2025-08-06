<?php
require_once __DIR__ . '/../config/db_connect.php';

class ProductsController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function setConnection(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function index($page = 1, $itemsPerPage = 5, $sort = 'name', $order = 'asc')
    {
        $offset = ($page - 1) * $itemsPerPage;
        $allowedSort = ['name', 'price', 'quantity_available'];
        if (!in_array($sort, $allowedSort)) {
            $sort = 'name';
        }

        $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';
        $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY $sort $order LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStmt = $this->conn->query("SELECT COUNT(*) FROM products");
        $totalProducts = $totalStmt->fetchColumn();
        $totalPages = ceil($totalProducts / $itemsPerPage);

        return [
            'products' => $products,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    }

    public function create($name, $price, $qty)
    {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $qty]);
    }

    public function update($id, $name, $price, $qty)
    {
        $stmt = $this->conn->prepare("UPDATE products SET name=?, price=?, quantity_available=? WHERE id=?");
        $stmt->execute([$name, $price, $qty, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM transactions WHERE product_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return false;
        } else {
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id=?");
            $stmt->execute([$id]);
            return true;
        }
    }

    public function purchase($productId, $quantity)
    {
        $userId = $_SESSION['user']['id'];

        if (!is_numeric($quantity) || $quantity <= 0) {
            die("Invalid quantity.");
        }

        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die("Product not found.");
        }

        if ($product['quantity_available'] < $quantity) {
            die("Not enough stock.");
        }

        $total = $product['price'] * $quantity;

        try {
            $this->conn->beginTransaction();
            $this->conn->prepare("UPDATE products SET quantity_available = quantity_available - ? WHERE id = ?")
                ->execute([$quantity, $productId]);
            $this->conn->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_price) 
                                  VALUES (?, ?, ?, ?)")
                ->execute([$userId, $productId, $quantity, $total]);
            $this->conn->commit();
            echo "You purchased $quantity of {$product['name']} for $$total.";
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
