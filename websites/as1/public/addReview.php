<?php
session_start();
require_once 'db.php';

// ws the form subbmited, it checks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $reviewText = trim($_POST['reviewText']);
    $auctionId = $_POST['auction_id'];

    // logged in cha ki nai ensure garcha
    if (isset($_SESSION['user_id']) && !empty($reviewText)) {
        $userId = $_SESSION['user_id'];

        // database ma review insert garne
        $stmt = $pdo->prepare("INSERT INTO review (reviewText, auction_id, reviewerName, reviewerEmail, userId, date) 
                               VALUES (:reviewText, :auction_id, :reviewerName, :reviewerEmail, :userId, NOW())");
        $stmt->execute([
            ':reviewText' => $reviewText,
            ':auction_id' => $auctionId,
            ':reviewerName' => $_SESSION['user_name'],  // user ko name suppose store garyo bhane
            ':reviewerEmail' => $_SESSION['user_email'], // same here but with email
            ':userId' => $userId
        ]);

        // auction page ma gayo aba
        header('Location: index.php');
        exit;
    } else {
        // login mai feri redirect gardincha if nlogged user haina bhane
        header('Location: login.php');
        exit;
    }
}
?>
