<?php
session_start();

// Check if the user has already logged in
if (isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $host = 'localhost'; // Change this to your database host
    $dbname = 'mydatabase'; // Change this to your database name
    $username_db = 'username'; // Change this to your database username
    $password_db = 'password'; // Change this to your database password
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $username_db, $password_db);

    // Check if the user exists in the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // User is authenticated, set session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];

        // Redirect to welcome page
        header('Location: login.php');
        exit();
    } else {
        // User is not authenticated, show error message
        $error = 'Invalid username or password';
    }
}
?>