<?php
require_once "config.php";
require_admin();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($user_id > 0) {

        if ($action === 'approve') {
            $status = 'active';
        } elseif ($action === 'disable') {
            $status = 'disabled';
        } elseif ($action === 'activate') {
            $status = 'active';
        } else {
            $status = '';
        }

        if ($status !== '') {
            $stmt = $conn->prepare(
                "UPDATE users SET status = ? WHERE id = ? AND role = 'user'"
            );
            $stmt->bind_param("si", $status, $user_id);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $message = "User account updated successfully!";
            } else {
                $message = "No account was updated.";
            }

            $stmt->close();
        }
    }
}

$result = $conn->query(
    "SELECT id, fullname, email, username, role, status, created_at
     FROM users ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management | Joyce's Bakery</title>
<link rel="stylesheet" href="style.css">

<style>
.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #b77b9c;
    color: white;
}

button {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 3px;
}

.approve {
    background: #4caf50;
    color: white;
}

.disable {
    background: #e57373;
    color: white;
}

.activate {
    background: #2196f3;
    color: white;
}

.alert {
    padding: 12px;
    background: #e8f5e9;
    margin: 15px 0;
}

@media(max-width: 700px) {
    .table-wrapper {
        overflow-x: auto;
    }
}
</style>
</head>

<body>

<nav>
    <a href="dashboard.php">Joyce's Bakery</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="products.php">Products</a>
    <a href="users.php">Users</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">

<h1>User Management</h1>
<p>Manage registered bakery staff accounts.</p>

<?php if ($message): ?>
<div class="alert"><?= e($message) ?></div>
<?php endif; ?>

<div class="table-wrapper">

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Username</th>
    <th>Role</th>
    <th>Status</th>
    <th>Registered</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php while ($user = $result->fetch_assoc()): ?>

<tr>
    <td><?= e($user['id']) ?></td>
    <td><?= e($user['fullname']) ?></td>
    <td><?= e($user['email']) ?></td>
    <td><?= e($user['username']) ?></td>
    <td><?= e($user['role']) ?></td>
    <td><?= e($user['status']) ?></td>
    <td><?= e($user['created_at']) ?></td>

    <td>

    <?php if ($user['role'] === 'user'): ?>

        <?php if ($user['status'] === 'pending'): ?>

            <form method="POST">
                <input type="hidden" name="user_id"
                       value="<?= e($user['id']) ?>">
                <input type="hidden" name="action" value="approve">
                <button class="approve">Approve</button>
            </form>

        <?php elseif ($user['status'] === 'active'): ?>

            <form method="POST">
                <input type="hidden" name="user_id"
                       value="<?= e($user['id']) ?>">
                <input type="hidden" name="action" value="disable">
                <button class="disable">Disable</button>
            </form>

        <?php elseif ($user['status'] === 'disabled'): ?>

            <form method="POST">
                <input type="hidden" name="user_id"
                       value="<?= e($user['id']) ?>">
                <input type="hidden" name="action" value="activate">
                <button class="activate">Activate</button>
            </form>

        <?php endif; ?>

    <?php else: ?>

        Administrator

    <?php endif; ?>

    </td>
</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>

<br>
<a href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>