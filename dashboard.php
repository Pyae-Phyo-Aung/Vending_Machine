<?php
require_once __DIR__ . '/config/db_connect.php';
require_once __DIR__ . '/controllers/ProductsController.php';
include 'views/layouts/header.php';
?>
<div class="col-md-6 offset-md-3 my-5">
  <h1>Welcome to Admin Dashboard</h1>
</div>
<?php
include 'views/layouts/footer.php';
?>