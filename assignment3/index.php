<?php
session_start();
require_once "pdo.php";
?>

<!DOCTYPE html>
<html>
<head><title>Automobile Database</title></head>
<body>
<div class="container">
    <?php if (!isset($_SESSION['name'])): ?>
        <h1>Welcome to the Automobile Database</h1>
        <p><a href="login.php">Please log in</a></p>
    <?php else: ?>
        <h1>Automobile Database</h1>
        <p>Welcome, <?= htmlentities($_SESSION['name']) ?>!</p>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
            unset($_SESSION['error']);
        }

        $stmt = $pdo->query("SELECT autos_id, make, model, year, mileage FROM autos");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0): ?>
            <table border="1">
                <tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th></tr>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlentities($row['make']) ?></td>
                        <td><?= htmlentities($row['model']) ?></td>
                        <td><?= htmlentities($row['year']) ?></td>
                        <td><?= htmlentities($row['mileage']) ?></td>
                        <td>
                            <a href="edit.php?autos_id=<?= $row['autos_id'] ?>">Edit</a> |
                            <a href="delete.php?autos_id=<?= $row['autos_id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No rows found</p>
        <?php endif; ?>

        <p>
            <a href="add.php">Add New Entry</a> |
            <a href="logout.php">Log Out</a>
        </p>
    <?php endif; ?>
</div>
</body>
</html>