<?php
session_start();
require 'db.php';

$searchQuery = $_GET['search'] ?? '';

$results = [];

if (!empty($searchQuery)) {
    $searchTerm = '%' . $searchQuery . '%';

    $stmt = $pdo->prepare("
        SELECT a.*, c.name AS categoryName, u.name AS sellerName,
        (CASE 
            WHEN a.title LIKE ? THEN 2
            WHEN a.description LIKE ? THEN 1
            ELSE 0
        END) AS relevance
        FROM auction a
        JOIN category c ON a.categoryId = c.id
        JOIN user u ON a.userId = u.id
        WHERE a.title LIKE ? OR a.description LIKE ?
        ORDER BY relevance DESC, a.endDate DESC
    ");

    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Carbuy Auctions</h1>
    <a href="index.php">Back to Listings</a>

    <form method="GET" action="search.php">
        <input type="text" name="search" placeholder="Search for cars..." value="<?php echo htmlspecialchars($searchQuery); ?>" required>
        <button type="submit">Search</button>
    </form>

    <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

    <?php if (!empty($results)): ?>
        <ul class="auction-list">
            <?php foreach ($results as $auction): ?>
                <li class="auction-item">
                    <h3><?php echo htmlspecialchars($auction['title']); ?></h3>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($auction['categoryName']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($auction['description']); ?></p>
                    <p><strong>Seller:</strong> <?php echo htmlspecialchars($auction['sellerName']); ?></p>
                    <p><strong>Ends:</strong> <?php echo htmlspecialchars($auction['endDate']); ?></p>
                    <a class="more auctionLink" href="auctionDetail.php?id=<?php echo $auction['id']; ?>">More</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
    <?php endif; ?>
</body>
</html>
