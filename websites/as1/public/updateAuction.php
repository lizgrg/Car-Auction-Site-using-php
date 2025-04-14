<?php
session_start();
include('db.php');

// logged in cha ki nai hai
if (!isset($_SESSION['user_id']) || !isset($_POST['auction_id'])) {
    die('Unauthorized or no auction selected.');
}

$auction_id = $_POST['auction_id'];
$user_id = $_SESSION['user_id'];

// input validation esle chai
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$starting_price = $_POST['starting_price'] ?? '';
$end_time = $_POST['end_time'] ?? '';

// database ma garnu paryo haii
$stmt = $pdo->prepare("UPDATE auction SET title = :title, description = :description, price = :starting_price, endDate = :end_time WHERE id = :auction_id AND userId = :user_id");
$stmt->execute([
    ':title' => $title,
    ':description' => $description,
    ':starting_price' => $starting_price,
    ':end_time' => $end_time,
    ':auction_id' => $auction_id,
    ':user_id' => $user_id
]);

// Redirect garcha esle pni
header("Location: index.php");
exit();
?>
