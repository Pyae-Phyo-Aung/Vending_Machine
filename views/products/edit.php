<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/ProductsController.php';
$controller = new ProductsController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update($_POST['id'], $_POST['name'], $_POST['price'], $_POST['qty']);
    header("Location: index.php");
    exit;
} else {
    $id = $_GET['id'];
    $response = $controller->index();
    $products = $response['products'];
    $product = null;
    foreach ($products as $p) {
        if ($p['id'] == $id) {
            $product = $p;
            break;
        }
    }
}
?>
<div class="col-md-6 offset-md-3 my-5">
    <h3>Edit Product</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Price:</label>
                <input type="text" name="price" class="form-control" value="<?= $product['price'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity:</label>
                <input type="number" name="qty" class="form-control" value="<?= $product['quantity_available'] ?>">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
</div>
<?php
include '../layouts/footer.php';
?>