<?php
session_start();
require_once 'db.php';

// feri pni loggied in user admin ho ki nai it checks
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// form submission handle garne
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['name'])) {
    $category_id = $_POST['id'];
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("UPDATE category SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $name, 'id' => $category_id]);
        header("Location: adminCategories.php?updated=1");
        exit();
    } else {
        $error = "Category name cannot be empty.";
    }
}

//  Load existing category
if (!isset($_GET['id'])) {
    die('Category ID not provided.');
}

$category_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM category WHERE id = :id");
$stmt->execute(['id' => $category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die('Category not found.');
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

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="editCategory.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($category['id']) ?>">

        <label for="name">Category Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>" required>

        <button type="submit">Save Changes</button>
    </form>

    <p><a href="adminCategories.php">Back to Categories</a></p>
</body>
</html>
