<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$sql = "
SELECT listings.*, cart.id AS cart_item_id 
FROM cart 
JOIN listings ON cart.listing_id = listings.id 
WHERE cart.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['price'];
}
$stmt->close();

// Get user profile info
$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $address);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        .checkout-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .checkout-section {
            background: #1e1e1e;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            color: #1abc9c;
        }

        .checkout-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: #2c2c2c;
            color: #fff;
        }

        .proceed-btn {
            background: #1abc9c;
            padding: 12px 25px;
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .proceed-btn:hover {
            background: #169d86;
        }

        .cancel-btn {
            background: #e74c3c;
        }

        .cancel-btn:hover {
            background: #c0392b;
        }

        .user-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #2c2c2c;
            border-radius: 8px;
        }

        .user-info p {
            margin: 5px 0;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout Summary</h2>

    <div class="checkout-section">
        <?php if (count($items) > 0): ?>
            <?php foreach ($items as $item): ?>
                <div class="checkout-item">
                    <span><?= htmlspecialchars($item['title']) ?></span>
                    <span>RM <?= number_format($item['price'], 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="checkout-item" style="font-weight: bold;">
                <span>Total</span>
                <span>RM <?= number_format($total, 2) ?></span>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <div class="user-info">
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    </div>

    <form action="process_payment.php" method="post" class="checkout-section">
        <h2>Contact & Delivery Info</h2>

        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input type="text" name="contact_number" id="contact_number" required value="<?= htmlspecialchars($phone) ?>">
        </div>

        <div class="form-group">
            <label for="delivery_address">Delivery Address</label>
            <textarea name="delivery_address" id="delivery_address" rows="4" required><?= htmlspecialchars($address) ?></textarea>
        </div>

        <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">

        <div class="button-group">
            <button type="submit" class="proceed-btn">Proceed to Pay</button>
            <button type="button" class="proceed-btn cancel-btn" onclick="window.location.href='cart.php'">Cancel Checkout</button>
        </div>
    </form>
</div>

</body>
</html>
