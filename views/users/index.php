<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/UsersController.php';
$controller = new UsersController();
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

    <h3>User List</h1>
        <table class="table table-striped table-hover">
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= $product['role'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $product['id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= $product['id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="../../dashboard.php">Back to Home</a>
</div>
<?php
include '../layouts/footer.php';
?>