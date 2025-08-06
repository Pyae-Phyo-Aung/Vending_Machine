<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/ProductsController.php';

$errors = [];
$name = '';
$price = '';
$qty = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $qty = trim($_POST['qty'] ?? '');

    if ($name === '') {
        $errors['name'] = "Name is required.";
    }
    if ($price === '') {
        $errors['price'] = "Price is required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors['price'] = "Price must be a positive number.";
    }
    if ($qty === '') {
        $errors['qty'] = "Quantity is required.";
    } elseif (!ctype_digit($qty) || (int)$qty < 0) {
        $errors['qty'] = "Quantity must be a non-negative integer.";
    }

    if (empty($errors)) {
        $controller = new ProductsController();
        $controller->create($name, $price, $qty);
        header("Location: index.php");
        exit;
    }
}
?>
<div class="col-md-6 offset-md-3 my-5">
    <h3>Add Product</h3>
    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : '' ?>" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['name']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Price:</label>
            <input type="text" name="price" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : '' ?>" value="<?php echo htmlspecialchars($price); ?>">
            <?php if (isset($errors['price'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['price']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity:</label>
            <input type="number" name="qty" class="form-control <?php echo isset($errors['qty']) ? 'is-invalid' : '' ?>" value="<?php echo htmlspecialchars($qty); ?>">
            <?php if (isset($errors['qty'])): ?>
                <div class="invalid-feedback">
                    <?php echo $errors['qty']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
</div>
<?php
include '../layouts/footer.php';
?>