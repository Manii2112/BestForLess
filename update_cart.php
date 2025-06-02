<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $cart_item_id = $_POST['cart_item_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$cart_item_id || !in_array($action, ['increase', 'decrease'])) {
        header("Location: cart.php");
        exit;
    }

    // Fetch current quantity and user ownership validation
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $cart_item_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($quantity);
    if (!$stmt->fetch()) {
        // Item not found or not owned by user
        $stmt->close();
        header("Location: cart.php");
        exit;
    }
    $stmt->close();

    if ($action === 'increase') {
        $new_quantity = $quantity + 1;
    } else {
        $new_quantity = max(1, $quantity - 1);
    }

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param('iii', $new_quantity, $cart_item_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: cart.php");
exit;
