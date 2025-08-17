<?php
session_start();
require_once "pdo.php";

// Stored salt and hash
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // password is php123

// Handle cancel
if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['who']) ? trim($_POST['who']) : '';
    $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    if (strlen($email) < 1 || strlen($pass) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    } elseif (strpos($email, '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt . $pass);
        if ($check == $stored_hash) {
            error_log("Login success $email");
            $_SESSION['name'] = $email;
            header("Location: view.php");
            return;
        } else {
            error_log("Login fail $email $check");
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Soham Rahul Jayakar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>

    <?php
    // Display flash error message
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red;">' . htmlentities($_SESSION['error']) . "</p>\n";
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST">
        <label for="nam">Email</label>
        <input type="text" name="who" id="nam"><br/>
        <label for="id_1723">Password</label>
        <input type="text" name="pass" id="id_1723"><br/>
        <input type="submit" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>

    <p>
        For a password hint, view source and find a password hint in the HTML comments.
        <!-- Hint: The password is the three character name of the programming language used in this class (all lower case) followed by 123. -->
    </p>
</div>
</body>
</html>