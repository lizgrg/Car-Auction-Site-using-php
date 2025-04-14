<?php
session_start();
require_once 'db.php';

//admin user ko lagi matra hai
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// category id provided cha ki nai
if (!isset($_GET['id'])) {
    die('Category ID is missing.');
}

$category_id = $_GET['id'];

//delete gardim
$stmt = $pdo->prepare("DELETE FROM category WHERE id = :id");
$stmt->execute(['id' => $category_id]);

// this chai redirects hai
header("Location: adminCategories.php?deleted=1");
exit();
