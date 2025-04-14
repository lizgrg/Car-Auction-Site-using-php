<?php
session_start();
require 'db.php';

$auctionId = $_GET['id'] ?? null;

if (!$auctionId) {
    echo "Auction ID not provided.";
    exit;
}

// auction ko detailss feri pani
$stmt = $pdo->prepare('SELECT a.*, c.name AS categoryName, u.name AS sellerName 
                       FROM auction a 
                       JOIN category c ON a.categoryId = c.id 
                       JOIN user u ON a.userId = u.id 
                       WHERE a.id = ?');
$stmt->execute([$auctionId]);
$auction = $stmt->fetch();

if (!$auction) {
    echo "Auction not found.";
    exit;
}

// sab bhanda higgest bid
$highestBidStmt = $pdo->prepare('SELECT MAX(bidAmount) AS maxBid FROM bid WHERE auctionId = ? AND bidAmount IS NOT NULL');
$highestBidStmt->execute([$auctionId]);
$highestBid = $highestBidStmt->fetchColumn();

// bid history pni liney
$bidStmt = $pdo->prepare('SELECT b.*, u.name FROM bid b JOIN user u ON b.userId = u.id WHERE b.auctionId = ? AND b.bidAmount IS NOT NULL ORDER BY b.date DESC');
$bidStmt->execute([$auctionId]);
$bids = $bidStmt->fetchAll();

// lisakepachi bid ko submission handle garne
if (isset($_POST['placeBid'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php?redirect_to=' . urlencode('auctionDetail.php?id=' . $auctionId));
        exit;
    }

    $userId = $_SESSION['user_id'];
    $bidAmount = floatval($_POST['bidAmount']);

    if ($bidAmount <= 0) {
        echo "Please enter a valid bid amount greater than zero.";
        exit;
    }

    $insertStmt = $pdo->prepare('INSERT INTO bid (bidAmount, auctionId, userId, date) VALUES (?, ?, ?, NOW())');
    $insertStmt->execute([$bidAmount, $auctionId, $userId]);

    header("Location: auctionDetail.php?id=" . $auctionId);
    exit;
}

// reviews hercha ya chai
$reviewFetchStmt = $pdo->prepare('SELECT r.*, u.name FROM review r JOIN user u ON r.userId = u.id WHERE r.auctionId = ? ORDER BY r.date DESC');
$reviewFetchStmt->execute([$auctionId]);
$reviews = $reviewFetchStmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($auction['title']) ?></title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>
    <div class="detail-container">
        <h1>Carbuy Auctions</h1>
        <a href="index.php" class="back-link">← Back to Listings</a>

        <h2><?= htmlspecialchars($auction['title']) ?></h2>
        <p><strong>Category:</strong> <?= htmlspecialchars($auction['categoryName']) ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($auction['description'])) ?></p>
        <p><strong>Seller:</strong> <?= htmlspecialchars($auction['sellerName']) ?></p>
        <p><strong>Auction ends:</strong> <?= htmlspecialchars($auction['endDate']) ?></p>

        <div class="highest-bid">
            <?= $highestBid ? 'Highest Bid: Rs ' . number_format($highestBid, 2) : 'No bids yet' ?>
        </div>

        <div class="bid-section">
            <h3>Place a Bid</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST" class="bid-form">
                    <label for="bidAmount">Your Bid (Rs):</label>
                    <input type="number" step="0.01" name="bidAmount" id="bidAmount" required>
                    <input type="submit" name="placeBid" value="Place Bid">
                </form>
            <?php else: ?>
                <p><a href="login.php?redirect_to=<?= urlencode('auctionDetail.php?id=' . $auctionId) ?>">Login to place a bid</a></p>
            <?php endif; ?>
        </div>

        <div class="history-section">
            <h3>Bid History</h3>
            <?php if ($bids): ?>
                <ul class="bid-list">
                    <?php foreach ($bids as $bid): ?>
                        <li><?= htmlspecialchars($bid['name']) ?> bid Rs <?= number_format($bid['bidAmount'], 2) ?> on <?= $bid['date'] ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No bids yet.</p>
            <?php endif; ?>
        </div>

        <div class="review-section">
            <h3>Reviews</h3>
            <?php if ($reviews): ?>
                <ul class="review-list">
                    <?php foreach ($reviews as $review): ?>
                        <li><strong><?= htmlspecialchars($review['name']) ?>:</strong> <?= htmlspecialchars($review['reviewText']) ?> (<?= $review['date'] ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
