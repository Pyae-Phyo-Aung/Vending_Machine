<?php
require_once __DIR__ . '../config/db_connect.php';
require_once __DIR__ . '../controllers/ProductsController.php';
include 'views/layouts/header.php';

$controller = new ProductsController();

$basePath = '/Vending_Machine-main';
$uri = $_SERVER['REQUEST_URI'];
$path = substr($uri, strlen($basePath));
$path = parse_url($path, PHP_URL_PATH);

if ($path === '/' || $path === '/products') {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $sort = $_GET['sort'] ?? 'name';
    $order = $_GET['order'] ?? 'asc';

    function nextOrder($currentOrder)
    {
        return $currentOrder === 'asc' ? 'desc' : 'asc';
    }

    $data = $controller->index($page, 5, $sort, $order);
    $products = $data['products'];
    $totalPages = $data['totalPages'];
    $currentPage = $data['currentPage'];
?>
    <div class="col-md-6 offset-md-3 my-5">
        <h3>Product List</h3>
        <table class="table table-striped table-hover">
            <tr>
                <th><a href="?sort=name&order=<?= ($sort === 'name' ? nextOrder($order) : 'asc') ?>&page=1">Name <?= $sort === 'name' ? ($order === 'asc' ? '▲' : '▼') : '' ?></a></th>
                <th><a href="?sort=price&order=<?= ($sort === 'price' ? nextOrder($order) : 'asc') ?>&page=1">Price <?= $sort === 'price' ? ($order === 'asc' ? '▲' : '▼') : '' ?></a></th>
                <th><a href="?sort=quantity_available&order=<?= ($sort === 'quantity_available' ? nextOrder($order) : 'asc') ?>&page=1">Quantity <?= $sort === 'quantity_available' ? ($order === 'asc' ? '▲' : '▼') : '' ?></a></th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= $product['quantity_available'] ?></td>
                    <td>
                        <?php if (isset($_SESSION['user']['id'])): ?>
                            <a href="./views/products/purchase.php?id=<?= $product['id'] ?>">Purchase</a>
                        <?php else: ?>
                            <a href="login.php">Login</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <nav class="mt-4">
            <ul class="pagination">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
<?php
    include 'views/layouts/footer.php';
} elseif ($path === '/products/purchase') {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = $_POST['id'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        if ($productId && $quantity) {
            $controller->purchase($productId, $quantity);
        } else {
            echo "Missing product ID or quantity.";
        }
    } else {
        echo "Invalid request method.";
    }
} else {
    http_response_code(404);
    echo "404 Not Found";
}
