<?php
session_start();
require_once 'db.php';

// admin lai matra allow garcha
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO category (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);

        header("Location: adminCategories.php");
        exit();
    } else {
        $error = "Category name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Category</h1>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post" action="addCategory.php">
        <label for="name">Category Name:</label>
        <input type="text" name="name" id="name" required>
        <input type="submit" value="Add Category">
    </form>
</body>
</html>
