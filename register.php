<?php
require_once "config.php";

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($fullname === '' || $email === '' || $username === '' || $password === '') {
        $error = "Please complete all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";

    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";

    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );

        $check->bind_param("ss", $username, $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $error = "Username or email is already registered.";

        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $role = 'user';
            $status = 'pending';

            $stmt = $conn->prepare(
                "INSERT INTO users
                (fullname, email, username, password, role, status)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssss",
                $fullname,
                $email,
                $username,
                $hashed,
                $role,
                $status
            );

            if ($stmt->execute()) {
                $message = "Registration successful! Please wait for admin approval.";
            } else {
                $error = "Registration failed: " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Joyce's Bakery</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-page">

<div class="auth-card">

    <h1>Joyce's Bakery</h1>
    <h2>Create Account</h2>

    <?php if ($message): ?>
        <p class="alert success"><?= e($message) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="alert error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Full Name:</label>
        <input type="text" name="fullname" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Register</button>

    </form>

    <p>Already registered? <a href="index.php">Login</a></p>

</div>

</body>
</html>