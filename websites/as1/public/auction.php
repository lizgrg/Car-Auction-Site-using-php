<?php
session_start();
require_once 'db.php';

$auctionId = $_GET['id'] ?? null;
if (!$auctionId) {
    header('Location: index.php');
    exit;
}

// details liney auciton ko
$stmt = $pdo->prepare("SELECT * FROM auction WHERE id = ?");
$stmt->execute([$auctionId]);
$auction = $stmt->fetch();

// reviews ko lagi fetch agrne hai
$stmt = $pdo->prepare("SELECT * FROM review WHERE auctionId = ?");
$stmt->execute([$auctionId]);
$reviews = $stmt->fetchAll();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $reviewText = trim($_POST['reviewText']);
    if (empty($reviewText)) {
        $error = "Review cannot be empty.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO review (auctionId, reviewerName, reviewerEmail, userId, reviewText, date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$auctionId, $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['user_id'], $reviewText]);
        header("Location: auction.php?id=$auctionId");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Auction Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?= htmlspecialchars($auction['title']) ?></h1>
    <p><?= htmlspecialchars($auction['description']) ?></p>
    <p>End Date: <?= htmlspecialchars($auction['endDate']) ?></p>
    <p>Category: <?= htmlspecialchars($auction['categoryId']) ?></p>

    
    <h2>Reviews</h2>
    <?php foreach ($reviews as $review): ?>
        <div>
            <strong><?= htmlspecialchars($review['reviewerName']) ?></strong>
            <p><?= htmlspecialchars($review['reviewText']) ?></p>
        </div>
    <?php endforeach; ?>

  
    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST">
            <label>Your Review:</label><br>
            <textarea name="reviewText" required></textarea><br><br>
            <button type="submit">Submit Review</button>
        </form>
    <?php else: ?>
        <p>You need to be logged in to leave a review.</p>
    <?php endif; ?>
</body>
</html>
