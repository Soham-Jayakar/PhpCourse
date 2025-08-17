<?php
session_start();
require_once "pdo.php";

// Access control
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

// Validate ID
if (!isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header("Location: index.php");
    return;
}

$id = $_GET['autos_id'];

// Handle POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = trim($_POST['make'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $mileage = trim($_POST['mileage'] ?? '');

    if (strlen($make) < 1 || strlen($model) < 1 || strlen($year) < 1 || strlen($mileage) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?autos_id=" . $id);
        return;
    } elseif (!is_numeric($year)) {
        $_SESSION['error'] = "Year must be an integer";
        header("Location: edit.php?autos_id=" . $id);
        return;
    } elseif (!is_numeric($mileage)) {
        $_SESSION['error'] = "Mileage must be an integer";
        header("Location: edit.php?autos_id=" . $id);
        return;
    }

    // Update record
    $stmt = $pdo->prepare("UPDATE autos SET make = :mk, model = :md, year = :yr, mileage = :mi WHERE autos_id = :id");
    $stmt->execute([
        ':mk' => $make,
        ':md' => $model,
        ':yr' => $year,
        ':mi' => $mileage,
        ':id' => $id
    ]);
    $_SESSION['success'] = "Record edited";
    header("Location: index.php");
    return;
}

// Fetch existing record
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
<head><title>Edit Auto</title></head>
<body>
<div class="container">
    <h1>Edit Automobile</h1>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
        unset($_SESSION['error']);
    }
    ?>

    <form method="post">
        <p>Make: <input type="text" name="make" value="<?= htmlentities($auto['make']) ?>"></p>
        <p>Model: <input type="text" name="model" value="<?= htmlentities($auto['model']) ?>"></p>
        <p>Year: <input type="text" name="year" value="<?= htmlentities($auto['year']) ?>"></p>
        <p>Mileage: <input type="text" name="mileage" value="<?= htmlentities($auto['mileage']) ?>"></p>
        <p><input type="submit" value="Save"></p>
    </form>
    <p><a href="index.php">Cancel</a></p>
</div>
</body>
</html>