<?php
require_once "config.php"; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); if ($id && $id != $_SESSION['user_id']) { $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'user'"); $stmt->bind_param("i", $id); $stmt->execute(); $stmt->close(); } }
header("Location: users.php"); exit();
?>
