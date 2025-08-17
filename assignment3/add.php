<?php
session_start();
require_once "pdo.php";

// Access control
if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}

// Handle cancel
if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = isset($_POST['make']) ? trim($_POST['make']) : '';
    $model = isset($_POST['model']) ? trim($_POST['model']) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';
    $mileage = isset($_POST['mileage']) ? trim($_POST['mileage']) : '';

    // Validation
    if (strlen($make) < 1 || strlen($model) < 1 || strlen($year) < 1 || strlen($mileage) < 1) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    if (!is_numeric($year)) {
        $_SESSION['error'] = "Year must be an integer";
        header("Location: add.php");
        return;
    }

    if (!is_numeric($mileage)) {
        $_SESSION['error'] = "Mileage must be an integer";
        header("Location: add.php");
        return;
    }

    // Insert record
    $stmt = $pdo->prepare("INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)");
    $stmt->execute([
        ':mk' => $make,
        ':md' => $model,
        ':yr' => $year,
        ':mi' => $mileage
    ]);
    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Automobile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Adding Automobile for <?= htmlentities($_SESSION['name']) ?></h1>

    <?php
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST">
        <label for="make">Make</label>
        <input type="text" name="make" id="make"><br/>
        <label for="model">Model</label>
        <input type="text" name="model" id="model"><br/>
        <label for="year">Year</label>
        <input type="text" name="year" id="year"><br/>
        <label for="mileage">Mileage</label>
        <input type="text" name="mileage" id="mileage"><br/>
        <input type="submit" value="Add">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>
</html>