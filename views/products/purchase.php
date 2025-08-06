<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/ProductsController.php';
$baseUrl = '/Vending_Machine-main';
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$db = (new Database())->getConnection();
$controller = new ProductsController($db);

$productId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'] ?? 0;
    $controller->purchase($productId, $quantity);
    echo "<p><a href='$baseUrl'>Back to home</a></p>";
    exit();
} else {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found.";
        exit();
    }
}
?>
<div class="col-md-6 offset-md-3 my-5">
    <h3>Purchase Product</h1>
        <label for="quantity" class="form-label">Product:</label>
        <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" disabled>
        <label for="quantity" class="form-label">Price:</label>
        <input type="text" name="quantity" class="form-control" value="$<?= number_format($product['price'], 2) ?>" disabled>
        <p>Available: <?= $product['quantity_available'] ?></p>

        <form method="post">
            <label for="quantity" class="form-label">Quantity:</label>
            <input type="number" name="quantity" class="form-control" min="1" max="<?= $product['quantity_available'] ?>" required>
            <button type="submit" class="btn btn-primary my-3">Buy</button>
        </form>
        <a href="<?= $baseUrl ?>">Back to Home</a>
</div>

<?php
include '../layouts/footer.php';
?>