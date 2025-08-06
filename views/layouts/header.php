<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vending Machine</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar bg-body-tertiary bg-light">
    <div class="container-fluid">
      <div class="mx-5">
        <?php
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == "admin") {
        ?>
          <a class="navbar-brand text-dark" href="/Vending_Machine-main/dashboard.php">Vending Machine</a>
          <a class="navbar-brand" href="/Vending_Machine-main/views/products/index.php">Product</a>
          <a class="navbar-brand" href="/Vending_Machine-main/views/users/index.php">User</a>
          <a class="navbar-brand" href="/Vending_Machine-main/views/transactions/index.php">Transaction</a>
        <?php } else { ?>
          <a class="navbar-brand text-dark" href="/Vending_Machine-main/">Vending Machine</a>
        <?php } ?>
      </div>
      <ul class="d-flex nav justify-content-end mx-5 px-3">
        <?php
        if (isset($_SESSION['user']['id'])) {
          $username = htmlspecialchars($_SESSION['user']['name']);
          echo "<li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
              $username 
            </a>
            <ul class='dropdown-menu'>
              <li><a class='dropdown-item' href='/Vending_Machine-main/logout.php'>Logout</a></li>
            </ul>
          </li>";
        } else {
          echo "<li class='nav-item'>
                    <a class='btn btn-primary mr-3' href='/Vending_Machine-main/login.php'>Login</a>
                  </li>";
        }
        ?>
      </ul>
    </div>
  </nav>
  <div class="container-fluid">