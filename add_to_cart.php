<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listing_id'])) {
    $user_id = $_SESSION['user_id'];
    $listing_id = intval($_POST['listing_id']);

    // Check if the item is already in the cart
    $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND listing_id = ?");
    $check->bind_param("ii", $user_id, $listing_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        // Insert into cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, listing_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $listing_id);
        $stmt->execute();
        $stmt->close();
    }

    $check->close();
}

// Redirect back to home
header("Location: index.php");
exit;
