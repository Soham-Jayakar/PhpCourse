<?php
session_start();
require_once "pdo.php";

// Access control
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

$id = $_GET['autos_id'];

// Handle POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM autos WHERE autos_id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['success'] = "Record deleted";
        header("Location: index.php");
        return;
    } else {
        // Cancel pressed
        header("Location: index.php");
        return;
    }
}

// Fetch record to confirm
$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :id");
$stmt->execute([':id' => $id]);
$auto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auto) {
    $_SESSION['error'] = "Record not found";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head><title>Delete Auto</title></head>
<body>
<div class="container">
    <h1>Confirm Deletion</h1>
    <p>Are you sure you want to delete this automobile?</p>
    <ul>
        <li><strong>Make:</strong> <?= htmlentities($auto['make']) ?></li>
        <li><strong>Model:</strong> <?= htmlentities($auto['model']) ?></li>
        <li><strong>Year:</strong> <?= htmlentities($auto['year']) ?></li>
        <li><strong>Mileage:</strong> <?= htmlentities($auto['mileage']) ?></li>
    </ul>

    <form method="post">
        <input type="submit" name="delete" value="Delete">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>
</html>