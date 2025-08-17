<?php
session_start();

// Check for name parameter
if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}

require_once "pdo.php";

$error = false;
$success = false;

// Handle logout
if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}

// Handle form submission
if (isset($_POST['add'])) {
    $make = isset($_POST['make']) ? trim($_POST['make']) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';
    $mileage = isset($_POST['mileage']) ? trim($_POST['mileage']) : '';

    if (strlen($make) < 1) {
        $error = "Make is required";
    } elseif (!is_numeric($year) || !is_numeric($mileage)) {
        $error = "Mileage and year must be numeric";
    } else {
        // Insert into database using prepared statement
        $stmt = $pdo->prepare("INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)");
        $stmt->execute([
            ':mk' => $make,
            ':yr' => $year,
            ':mi' => $mileage
        ]);
        $success = "Record inserted";
    }
}

// Fetch all autos
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Automobile Tracker - <?php echo htmlentities($_GET['name']); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Tracking Autos for <?php echo htmlentities($_GET['name']); ?></h1>

    <?php
    if ($error !== false) {
        echo '<p style="color:red;">' . htmlentities($error) . "</p>\n";
    }
    if ($success !== false) {
        echo '<p style="color:green;">' . htmlentities($success) . "</p>\n";
    }
    ?>

    <form method="POST">
        <label for="make">Make</label>
        <input type="text" name="make" id="make"><br/>
        <label for="year">Year</label>
        <input type="text" name="year" id="year"><br/>
        <label for="mileage">Mileage</label>
        <input type="text" name="mileage" id="mileage"><br/>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="logout" value="Logout">
    </form>

    <h2>Automobiles</h2>
    <ul>
        <?php
        foreach ($rows as $row) {
            echo "<li>";
            echo htmlentities($row['year']) . " " . htmlentities($row['make']) . " / " . htmlentities($row['mileage']);
            echo "</li>\n";
        }
        ?>
    </ul>
</div>
</body>
</html>