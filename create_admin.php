<?php
require_once "config.php";
$count = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'admin'")->fetch_assoc()['total'];
$message = ""; $error = "";
if ($count > 0) { $message = "An administrator account already exists. For security, this setup page is locked."; }
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); $password = $_POST['password'] ?? '';
    if ($username === '' || strlen($password) < 6) $error = "Enter a username and a password with at least 6 characters.";
    else { $hash = password_hash($password, PASSWORD_DEFAULT); $role = 'admin'; $status = 'active'; $stmt = $conn->prepare("INSERT INTO users (username, password, role, status) VALUES (?, ?, ?, ?)"); $stmt->bind_param("ssss", $username, $hash, $role, $status); if ($stmt->execute()) $message = "Admin account created. You can now log in."; else $error = "Could not create admin account. The username may already exist."; $stmt->close(); }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Create Admin | Joyce's Bakery</title><link rel="stylesheet" href="style.css"></head><body class="auth-page"><div class="auth-card"><h1>Joyce's Bakery</h1><h2>First Admin Setup</h2><?php if ($message): ?><p class="alert success"><?= e($message) ?></p><?php endif; ?><?php if ($error): ?><p class="alert error"><?= e($error) ?></p><?php endif; ?><?php if ((int)$count === 0): ?><form method="post"><label>Admin Username:</label><input name="username" required><label>Admin Password:</label><input type="password" name="password" required><button type="submit">Create Admin</button></form><?php endif; ?><p><a href="index.php">Go to Login</a></p></div></body></html>
