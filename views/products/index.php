<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/ProductsController.php';
$controller = new ProductsController();
$products = $controller->index();
$successMsg = '';
$errorMsg = '';

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    unset($_SESSION['successMsg']);
}

if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    unset($_SESSION['errorMsg']);
}
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$data = $controller->index($page);
$products = $data['products'];
$totalPages = $data['totalPages'];
$currentPage = $data['currentPage'];
?>
<div class="col-md-6 offset-md-3 my-5">
    <?php if ($successMsg): ?>
        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">
            <?= htmlspecialchars($successMsg) ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <?= htmlspecialchars($errorMsg) ?>
        </div>
    <?php endif; ?>
    <h3>Product List</h1>
        <a href="create.php">Add Product</a>
        <table class="table table-striped table-hover">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>$<?= $product['price'] ?></td>
                    <td><?= $product['quantity_available'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $product['id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= $product['id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <nav class="mt-4">
            <ul class="pagination">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <a href="../../dashboard.php">Back to Home</a>
</div>

<?php
include '../layouts/footer.php';
?>