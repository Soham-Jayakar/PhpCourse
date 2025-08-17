<?php
session_start();
require_once "pdo.php";

// Access control
if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

// Handle logout via POST
if (isset($_POST['logout'])) {
    header("Location: logout.php");
    return;
}

// Fetch autos
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Automobile Tracker</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red;">' . htmlentities($_SESSION['error']) . "</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green;">' . htmlentities($_SESSION['success']) . "</p>\n";
        unset($_SESSION['success']);
    }
    ?>

    <h2>Automobiles</h2>
    <ul>
        <?php foreach ($rows as $row): ?>
            <li><?= htmlentities($row['year']) ?> <?= htmlentities($row['make']) ?> / <?= htmlentities($row['mileage']) ?></li>
        <?php endforeach; ?>
    </ul>

    <form method="post">
        <a href="add.php">Add New</a> |
        <input type="submit" name="logout" value="Logout">
    </form>
</div>
</body>
</html>