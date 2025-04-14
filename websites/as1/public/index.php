<?php
session_start();
require_once 'db.php';

// category chaincha
$categories = $pdo->query("SELECT name FROM category ORDER BY name ASC")->fetchAll();

//search handle garcha hai
$searchQuery = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';

if (!empty($searchQuery)) {
    $searchTerm = '%' . $searchQuery . '%';

    $sql = "
        SELECT a.*, c.name AS category_name, u.name AS username,
               (SELECT MAX(bidAmount) FROM bid WHERE auctionId = a.id AND bidAmount IS NOT NULL) AS currentBid,
               (CASE 
                    WHEN a.title LIKE :term THEN 2
                    WHEN a.description LIKE :term THEN 1
                    ELSE 0
                END) AS relevance
        FROM auction a
        JOIN category c ON a.categoryId = c.id
        JOIN user u ON a.userId = u.id
        WHERE a.title LIKE :term OR a.description LIKE :term
        ORDER BY relevance DESC, a.id DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['term' => $searchTerm]);
    $auctions = $stmt->fetchAll();
} elseif (!empty($categoryFilter) && $categoryFilter !== 'All') {
    $stmt = $pdo->prepare("
        SELECT a.*, c.name AS category_name, u.name AS username,
               (SELECT MAX(bidAmount) FROM bid WHERE auctionId = a.id AND bidAmount IS NOT NULL) AS currentBid
        FROM auction a
        JOIN category c ON a.categoryId = c.id
        JOIN user u ON a.userId = u.id
        WHERE c.name = :category
        ORDER BY a.id DESC
    ");
    $stmt->execute(['category' => $categoryFilter]);
    $auctions = $stmt->fetchAll();
} else {
    $auctions = $pdo->query("
        SELECT a.*, c.name AS category_name, u.name AS username,
               (SELECT MAX(bidAmount) FROM bid WHERE auctionId = a.id AND bidAmount IS NOT NULL) AS currentBid
        FROM auction a
        JOIN category c ON a.categoryId = c.id
        JOIN user u ON a.userId = u.id
        ORDER BY a.id DESC
    ")->fetchAll();
}

// submission guide garcha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!empty($_POST['review']) && !empty($_POST['auction_id'])) {
        $reviewText = $_POST['review'];
        $auctionId = $_POST['auction_id'];

        $reviewerName = $_SESSION['username'] ?? 'Guest';
        $reviewerEmail = $_SESSION['email'] ?? 'guest@carbuy.com';
        $reviewerId = $_SESSION['user_id'] ?? null;

        $stmt = $pdo->prepare("INSERT INTO review (reviewText, reviewerName, reviewerEmail, auctionId, reviewerId, date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$reviewText, $reviewerName, $reviewerEmail, $auctionId, $reviewerId]);

        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Please enter a review.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carbuy Auctions</title>
    <link rel="stylesheet" href="carbuy.css" />
</head>
<body>
<header>
    <h1><span class="C">C</span><span class="a">a</span><span class="r">r</span><span class="b">b</span><span class="u">u</span><span class="y">y</span></h1>

    <div class="header-actions">
        <form action="index.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search for a car" value="<?= htmlspecialchars($searchQuery) ?>" />
            <input type="submit" value="Search" />
        </form>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="login-button">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login-button">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>


    <nav>
        <ul>
            <?php foreach ($categories as $cat): ?>
                <li><a href="index.php?category=<?= urlencode($cat['name']) ?>" class="categoryLink"><?= htmlspecialchars($cat['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <img src="banners/1.jpg" alt="Banner" />

    <main>
        <h1>Latest Car Listings</h1>

        <?php if (!empty($categoryFilter)): ?>
            <h2>Showing results for category: <strong><?= htmlspecialchars($categoryFilter) ?></strong></h2>
        <?php elseif (!empty($searchQuery)): ?>
            <h2>Search results for: <strong><?= htmlspecialchars($searchQuery) ?></strong></h2>
        <?php endif; ?>

        <ul class="carList">
        <?php foreach ($auctions as $auction): ?>
            <li>
                
                <img src="<?= $auction['image'] ? 'public/images/auctions/' . htmlspecialchars($auction['image']) : 'car.png' ?>" alt="Car image">

                <article>
                    <h2><?= htmlspecialchars($auction['title']) ?></h2>
                    <h3><?= htmlspecialchars($auction['category_name']) ?></h3>
                    <p><?= htmlspecialchars($auction['description']) ?></p>
                    <p class="price">
                        Current bid:
                        <?php
                        if ($auction['currentBid']) {
                            echo '$' . number_format($auction['currentBid'], 2);
                        } else {
                            echo 'TBD';
                        }
                        ?>
                    </p>
                    <p class="end-time">Auction ends: <?= htmlspecialchars($auction['endDate']) ?></p>
                    <a href="auctionDetail.php?id=<?= $auction['id'] ?>" class="more auctionLink">More &gt;&gt;</a>

                    <div class="reviews">
                        <h4>Reviews:</h4>
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM review WHERE auctionId = ?");
                        $stmt->execute([$auction['id']]);
                        $reviews = $stmt->fetchAll();

                        if ($reviews):
                        ?>
                            <ul>
                                <?php foreach ($reviews as $review): ?>
                                    <li><strong><?= htmlspecialchars($review['reviewerName']) ?>:</strong> <?= htmlspecialchars($review['reviewText']) ?> <em>(<?= htmlspecialchars($review['date']) ?>)</em></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No reviews yet.</p>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST" class="review-form">
                            <input type="hidden" name="auction_id" value="<?= $auction['id'] ?>">
                            <textarea name="review" placeholder="Write your review..." required></textarea>
                            <button type="submit" name="submit_review">Post Review</button>
                        </form>
                    <?php else: ?>
                        <p>You must be logged in to post a review.</p>
                    <?php endif; ?>
                </article>
            </li>
        <?php endforeach; ?>
        </ul>

        <hr />

        <div style="margin-bottom: 20px;">
            <a href="addAuction.php"><button style="margin-right: 10px;">Add Auction</button></a>

            <?php
            if (isset($_SESSION['user_id'])) {
                $stmt = $pdo->prepare("SELECT id FROM auction WHERE userId = :user_id LIMIT 1");
                $stmt->execute([':user_id' => $_SESSION['user_id']]);
                $userAuction = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userAuction) {
                    echo '<a href="editAuction.php?auction_id=' . $userAuction['id'] . '">
                        <button>Edit Auction</button>
                    </a>';
                }
            }
            ?>
        </div>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="adminCategories.php"><button style="background-color: #28a745; color: white; margin-bottom: 20px;">Manage Categories</button></a>
        <?php endif; ?>

        <hr />

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a>.</p>
            </div>
        <?php endif; ?>

        <footer>
            &copy; Carbuy 2024
        </footer>
    </main>
</body>
</html>
