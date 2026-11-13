<?php
require_once "config.php";

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Please enter your username and password.";
    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password, role, status
             FROM users
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $account = $result->fetch_assoc();

        if ($account && password_verify($password, $account['password'])) {

            if ($account['status'] === 'pending') {
                $error = "Your account is waiting for admin approval.";

            } elseif ($account['status'] === 'disabled') {
                $error = "Your account has been disabled.";

            } else {

                session_regenerate_id(true);

                $_SESSION['user_id'] = $account['id'];
                $_SESSION['username'] = $account['username'];
                $_SESSION['role'] = $account['role'];

                header("Location: dashboard.php");
                exit();
            }

        } else {
            $error = "Incorrect username or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Joyce's Bakery | Login</title>

<link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">

<div class="auth-card">

    <h1>Joyce's Bakery</h1>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p class="alert error">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Register here</a>
    </p>

</div>

</body>
</html>