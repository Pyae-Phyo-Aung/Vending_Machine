<?php
session_start();
require_once __DIR__ . '../../../controllers/ProductsController.php';
$controller = new ProductsController();
$id = $_GET['id'];
$deleted = $controller->delete($id);
if ($deleted) {
  $_SESSION['successMsg'] = "Product deleted successfully";
} else {
  $_SESSION['errorMsg'] = "Cannot delete product with existing transactions";
}

header("Location: index.php");
exit;
