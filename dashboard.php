<?php
require_once "config.php"; require_login();
$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'active'")->fetch_assoc()['total'];
$pendingCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE status = 'pending'")->fetch_assoc()['total'];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Dashboard | Joyce's Bakery</title><link rel="stylesheet" href="style.css"></head><body>
<?php include "navbar.php"; ?><main class="main"><h1>Welcome to Joyce's Bakery</h1><p>Hello, <?= e($_SESSION['username']) ?>!</p><div class="stats"><div class="stat"><h3>Bakery Products</h3><strong><?= e($productCount) ?></strong></div><div class="stat"><h3>Active Users</h3><strong><?= e($userCount) ?></strong></div><?php if ($_SESSION['role']==='admin'): ?><div class="stat"><h3>Pending Accounts</h3><strong><?= e($pendingCount) ?></strong></div><?php endif; ?></div><p>Use the navigation links to manage bakery products and user accounts.</p></main></body></html>
