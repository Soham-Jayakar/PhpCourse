<?php
session_start();
require_once "pdo.php";

// Access control
if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

// Handle cancel
if (isset($_POST['cancel'])) {
    header("Location: view.php");
    return;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = isset($_POST['make']) ? trim($_POST['make']) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';
    $mileage = isset($_POST['mileage']) ? trim($_POST['mileage']) : '';

    if (strlen($make) < 1) {
        $_SESSION['error'] = "Make is required";
    } elseif (!is_numeric($year) || !is_numeric($mileage)) {
        $_SESSION['error'] = "Mileage and year must be numeric";
    } else {
        $stmt = $pdo->prepare("INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)");
        $stmt->execute([
            ':mk' => $make,
            ':yr' => $year,
            ':mi' => $mileage
        ]);
        $_SESSION['success'] = "Record inserted";
    }
    header("Location: view.php");
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

    <form method="POST">
        <label for="make">Make</label>
        <input type="text" name="make" id="make"><br/>
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