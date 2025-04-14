<?php
session_start();
require_once 'db.php';

// hi guys so this allows you to nvaigate or redired to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=addAuction.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $categoryId = $_POST['category'] ?? null;
    $endDate = $_POST['endDate'];
    $userId = $_SESSION['user_id'];
    $imageName = null;

    // so imp to validate imput
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($categoryId)) $errors[] = "Category is required.";
    if (empty($endDate)) $errors[] = "End date is required.";


    // esle chai helps to insert auction lai database ma
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO auction (title, description, categoryId, endDate, userId, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $categoryId, $endDate, $userId, $imageName]);

        //id lincha recently or last ko
        $auctionId = $pdo->lastInsertId();

        // auction ko detail wala page ma redirect agrcha
        header("Location: auction.php?id=$auctionId");
        exit;
    }
}

// this is for fetching hai
$categories = $pdo->query("SELECT id, name FROM category")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Auction</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add New Auction</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea><br><br>

        <label>Category:</label><br>
        <select name="category" required>
            <option value="">--Select--</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_POST['category']) && $_POST['category'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Auction End Date:</label><br>
        <input type="date" name="endDate" value="<?= htmlspecialchars($_POST['endDate'] ?? '') ?>" required><br><br>

        <label>Upload Image (optional):</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit">Add Auction</button>
    </form>

</body>
</html>
