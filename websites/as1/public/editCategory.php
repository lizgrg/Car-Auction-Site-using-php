<?php
session_start();
require_once 'db.php';

// admin users only laaa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Category ID not specified.");
}

$category_id = $_GET['id'];
$error = '';
$success = '';

// idk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $new_name = trim($_POST['name']);

    if (!empty($new_name)) {
        $stmt = $pdo->prepare("UPDATE category SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $new_name, 'id' => $category_id]);
        $success = "Category updated successfully.";
    } else {
        $error = "Category name cannot be empty.";
    }
}

// fetch ganre 
$stmt = $pdo->prepare("SELECT name FROM category WHERE id = :id");
$stmt->execute(['id' => $category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Category not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Category</h1>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="name">Category Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        <button type="submit">Update Category</button>
    </form>

    <p><a href="adminCategories.php">Back to Categories</a></p>
</body>
</html>
