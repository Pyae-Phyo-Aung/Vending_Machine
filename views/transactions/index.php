<?php
require_once __DIR__ . '../../layouts/header.php';
require_once __DIR__ . '../../../controllers/UsersController.php';

$db = (new Database())->getConnection();
$sql = "SELECT 
            t.quantity, 
            t.total_price, 
            t.transaction_date, 
            u.name, 
            p.name AS product_name
        FROM 
            transactions t
        JOIN users u ON t.user_id = u.id
        JOIN products p ON t.product_id = p.id
        ORDER BY t.transaction_date DESC
        ";

$stmt = $db->query($sql);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-md-6 offset-md-3 my-5">
    <h3>Transactions List</h2>
        <table class="table table-striped table-hover">
            <tr>
                <th>User</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
            <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['product_name']) ?></td>
                    <td><?= $t['quantity'] ?></td>
                    <td>$<?= number_format($t['total_price'], 2) ?></td>
                    <td><?= $t['transaction_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="../../dashboard.php">Back to Home</a>
</div>

<?php
require_once __DIR__ . '../../layouts/footer.php';
?>