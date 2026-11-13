<?php
require_once "config.php"; require_login();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: products.php"); exit(); }
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?"); $stmt->bind_param("i", $id); $stmt->execute(); $product = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$product) { header("Location: products.php"); exit(); }
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['savebtn'])) {
    $name = trim($_POST['product_name'] ?? ''); $desc = trim($_POST['description'] ?? '');
    $quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT); $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    if ($name === '' || $quantity === false || $quantity < 0 || $price === false || $price < 0) $error = "Please enter valid product details.";
    else {
        $update = $conn->prepare("UPDATE products SET name = ?, description = ?, quantity = ?, price = ? WHERE id = ?");
        $update->bind_param("ssidi", $name, $desc, $quantity, $price, $id);
        if ($update->execute()) { header("Location: products.php?updated=1"); exit(); } else $error = "Something went wrong. Please try again.";
        $update->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Edit Product | Joyce's Bakery</title><link rel="stylesheet" href="style.css"></head><body><?php include "navbar.php"; ?><main class="main narrow"><h1>Edit Product ID <?= e($id) ?></h1><?php if ($error): ?><p class="alert error"><?= e($error) ?></p><?php endif; ?><form method="post" class="panel"><label>Product Name:</label><input type="text" name="product_name" value="<?= e($product['name']) ?>" required><label>Description:</label><textarea name="description" rows="4"><?= e($product['description']) ?></textarea><label>Quantity:</label><input type="number" name="quantity" min="0" value="<?= e($product['quantity']) ?>" required><label>Price:</label><input type="number" name="price" min="0" step="0.01" value="<?= e($product['price']) ?>" required><button type="submit" name="savebtn">Save Changes</button> <a class="small-button" href="products.php">Cancel</a></form></main></body></html>
