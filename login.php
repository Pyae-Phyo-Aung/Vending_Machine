<?php
include 'views/layouts/header.php';
require_once __DIR__ . '/config/db_connect.php';
$database = new Database();
$conn = $database->getConnection();
?>
<?php
if (!isset($_GET['message'])) {
    $message = "";
} else {
    $mailMessage = $_GET['message'];
    $message = "<div class='alert alert-success' role='alert'>" . htmlspecialchars($mailMessage) . "</div>";
}

$mailError = "";
$passwordError = "";
$oldMail = "";
$loginError = "";
if (isset($_POST['login-btn'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $_SESSION['old-name'] = $name;
    if (!$name && !$password) {
        $mailError = "name require.";
        $passwordError = "Password require.";
    } elseif (!$name) {
        $mailError = "name require.";
    } elseif (!$password) {
        $oldMail = $_SESSION['old-name'];
        $passwordError = "Password require.";
    } else {
        $sql = "SELECT * FROM users WHERE name = :name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $loginError = "<div class='alert alert-danger' role='alert'>
                                Incorrect name or password.
                            </div>";
        } else {
            if (!password_verify($password, $row['password'])) {
                $loginError = "<div class='alert alert-danger' role='alert'>
                                    Incorrect name or password.
                                </div>";
            } else {
                session_start();
                $_SESSION['user'] = $row;
                if (strtolower(trim($_SESSION['user']['role'])) == "admin") {
                    header("Location: dashboard.php");
                    exit;
                } else {
                    header("Location: /vending_machine/");
                    exit;
                }
            }
        }
    }
}
?>
<div class="container-fluid">
    <div class="row  justify-content-md-center">
        <div class="col-4 mt-5">
            <div class="card shadow">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <?php echo $loginError; ?>
                    <?php echo $message; ?>
                    <form action="#" method="post">
                        <div class="form-group">
                            <label for="">
                                <i class="text-primary fas fa-columns"></i>
                                User Name
                            </label>
                            <input class="form-control" type="name" name="name" placeholder="John" value="<?php echo $oldMail ?? ''; ?>">
                            <span class="text-danger"><?php echo $mailError; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="">
                                <i class="text-primary fas fa-key"></i>
                                Password
                            </label>
                            <input class="form-control" type="password" name="password" placeholder="*********">
                            <span class="text-danger"><?php echo $passwordError; ?></span>
                        </div>
                        <br>
                        <div class="form-group">
                            <button name="login-btn" class="btn btn-primary form-control">Login</button>
                        </div>
                        <div class="form-group text-center">
                            <p>Not a member?<a href="./views/users/create.php">Sign Up</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'views/layouts/footer.php';
?>