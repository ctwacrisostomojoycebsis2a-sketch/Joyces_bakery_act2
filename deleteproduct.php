<?php
require_once "config.php"; require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) { $stmt = $conn->prepare("DELETE FROM products WHERE id = ?"); $stmt->bind_param("i", $id); $stmt->execute(); $stmt->close(); }
}
header("Location: products.php"); exit();
?>
