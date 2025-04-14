<?php
session_start();
include('db.php');

// auction_id pass bhako cha ki nai checks
if (!isset($_SESSION['user_id']) || !isset($_POST['auction_id'])) {
    die('Unauthorized or no auction selected.');
}

$auction_id = $_POST['auction_id'];
$user_id = $_SESSION['user_id'];

// auction lai delete pni garna paryo
$stmt = $pdo->prepare("DELETE FROM auction WHERE id = :auction_id AND userId = :user_id");
$stmt->execute([
    ':auction_id' => $auction_id,
    ':user_id' => $user_id
]);

// feri index ma lagyo
header("Location: index.php");
exit();
?>
