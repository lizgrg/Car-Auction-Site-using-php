<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect_to=userReviews.php");
    exit;
}

// Fetch the reviews submitted by the logged-in user
$stmt = $pdo->prepare("
    SELECT r.*, a.title
    FROM review r
    JOIN auction a ON r.id = a.id
    WHERE r.userId = ?
    ORDER BY r.date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reviews = $stmt->fetchAll();

// Handle review deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $reviewId = $_GET['delete'];

    // Check if the review belongs to the logged-in user yaaah
    $stmt = $pdo->prepare("SELECT * FROM review WHERE id = ? AND userId = ?");
    $stmt->execute([$reviewId, $_SESSION['user_id']]);
    $review = $stmt->fetch();

    if ($review) {
        // deleteee
        $stmt = $pdo->prepare("DELETE FROM review WHERE id = ?");
        $stmt->execute([$reviewId]);
        header("Location: userReviews.php");
        exit;
    } else {
        // user ko review ho ki nai
        echo "You cannot delete this review.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Reviews</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Your Reviews</h1>

    <?php if (empty($reviews)): ?>
        <p>You have not written any reviews yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($reviews as $review): ?>
                <li>
                    <strong>Review for Auction: <?= htmlspecialchars($review['auctionTitle']) ?></strong><br>
                    <p><?= htmlspecialchars($review['reviewText']) ?></p>
                    <p><small>Posted on: <?= htmlspecialchars($review['date']) ?></small></p>

                
                    <a href="editReview.php?id=<?= $review['id'] ?>">Edit</a> | 
                    <a href="userReviews.php?delete=<?= $review['id'] ?>" onclick="return confirm('Are you sure you want to delete this review?')">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="index.php">Back to Auction Listings</a></p>
</body>
</html>
