<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/UsersController.php';
$controller = new UsersController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update($_POST['id'], $_POST['name'], $_POST['role']);
    header("Location: index.php");
    exit;
} else {
    $id = $_GET['id'];
    $users = $controller->index();
    foreach ($users as $u) {
        if ($u['id'] == $id) {
            $user = $u;
            break;
        }
    }
}
?>
<div class="col-md-6 offset-md-3 my-5">
    <h3>Edit User</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Role:</label>
                <input type="text" name="role" class="form-control" value="<?= $user['role'] ?>">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
</div>
<?php
include '../layouts/footer.php';
?>