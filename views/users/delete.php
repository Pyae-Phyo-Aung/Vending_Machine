<?php
session_start();
require_once __DIR__ . '../../../controllers/UsersController.php';
$controller = new UsersController();
$id = $_GET['id'];
$deleted = $controller->delete($id);
if ($deleted) {
  $_SESSION['successMsg'] = "User deleted successfully";
} else {
  $_SESSION['errorMsg'] = "Cannot delete user with existing transactions";
}

header("Location: index.php");
exit;
