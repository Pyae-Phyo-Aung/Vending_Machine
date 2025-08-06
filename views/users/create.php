<?php
include '../layouts/header.php';
require_once __DIR__ . '../../../controllers/UsersController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $controller = new UsersController();
  $controller->create($_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['role']);
  header("location:../../login.php");
  exit;
}
?>
<div class="container-fluid">
  <div class="row  justify-content-md-center">
    <div class="col-4 mt-3">
      <div class="card">
        <div class="card-header">
          <h4>Register</h4>
        </div>
        <div class="card-body">
          <form action="#" method="post">
            <div class="form-group">
              <label for="">
                User Name
              </label>
              <input class="form-control" type="text" name="name" placeholder="John" value="">
            </div>
            <div class="form-group">
              <label for="">
                Password
              </label>
              <input class="form-control" min="8" type="password" name="password" placeholder="*********">
            </div>
            <div class="form-group">
              <label for="">
                Role
              </label>
              <input class="form-control" min="11" type="text" name="role" placeholder="User" value="">
            </div>

            <br>
            <div class="form-group">
              <button name="register-btn" class="btn btn-primary form-control">Register</button>
            </div>
            <div class="form-group text-center">
              <a href="../../login.php">Already have an account?</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include '../layouts/footer.php';
?>