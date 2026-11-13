<?php
require_once "config.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";
$error = "";

// ADD PRODUCT
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['add_product'])) {

    $name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $quantity = filter_var(
        $_POST['quantity'] ?? null,
        FILTER_VALIDATE_INT
    );

    $price = filter_var(
        $_POST['price'] ?? null,
        FILTER_VALIDATE_FLOAT
    );

    if (
        $name === '' ||
        $quantity === false ||
        $quantity < 0 ||
        $price === false ||
        $price < 0
    ) {
        $error = "Please enter valid product information.";

    } else {

        $created_by = $_SESSION['user_id'];

        $stmt = $conn->prepare(
            "INSERT INTO products
            (name, description, quantity, price, created_by)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssidi",
            $name,
            $description,
            $quantity,
            $price,
            $created_by
        );

        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $error = "Unable to add product: " . $stmt->error;
        }

        $stmt->close();
    }
}

// GET PRODUCTS
$products = $conn->query(
    "SELECT * FROM products ORDER BY id DESC"
);

if (!$products) {
    die("Database error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Bakery Products | Joyce's Bakery</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "navbar.php"; ?>

<main class="main">

    <h1>Joyce's Bakery Products</h1>

    <p>
        Welcome,
        <?= e($_SESSION['username']) ?>!
    </p>

    <?php if ($message): ?>
        <p class="alert success">
            <?= e($message) ?>
        </p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="alert error">
            <?= e($error) ?>
        </p>
    <?php endif; ?>


    <!-- ADD PRODUCT -->

    <section class="panel">

        <h2>Add Bakery Product</h2>

        <form method="POST" class="product-form">

            <div>

                <label>Product Name:</label>

                <input
                    type="text"
                    name="product_name"
                    placeholder="e.g. Chocolate Cake"
                    required
                >

                <label>Description:</label>

                <textarea
                    name="description"
                    rows="4"
                    placeholder="Enter product description"
                ></textarea>

            </div>

            <div>

                <label>Quantity:</label>

                <input
                    type="number"
                    name="quantity"
                    min="0"
                    required
                >

                <label>Price (₱):</label>

                <input
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    required
                >

                <button type="submit" name="add_product">
                    Add Product
                </button>

            </div>

        </form>

    </section>


    <!-- PRODUCT LIST -->

    <section class="panel">

        <h2>Product List</h2>

        <div class="table-wrap">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date Created</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if ($products->num_rows > 0): ?>

                        <?php while ($row = $products->fetch_assoc()): ?>

                            <tr>

                                <td><?= e($row['id']) ?></td>

                                <td><?= e($row['name']) ?></td>

                                <td><?= e($row['description']) ?></td>

                                <td><?= e($row['quantity']) ?></td>

                                <td>
                                    ₱<?= number_format(
                                        (float)$row['price'],
                                        2
                                    ) ?>
                                </td>

                                <td>
                                    <?= e($row['created_at'] ?? '') ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6">
                                No bakery products added yet.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

</body>
</html>