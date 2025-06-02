<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT listings.*, cart.id AS cart_item_id, cart.quantity
FROM cart 
JOIN listings ON cart.listing_id = listings.id 
WHERE cart.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #1abc9c;
        }

        .cart-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cart-item {
            display: flex;
            background: #1e1e1e;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
        }

        .cart-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .cart-details {
            flex: 1;
        }

        .cart-details h3 {
            margin: 0 0 5px;
        }

        .cart-details p {
            margin: 0;
            color: #1abc9c;
            font-weight: bold;
        }

        .quantity-control {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-control form {
            display: inline;
        }

        .quantity-btn {
            background-color: #00c896;
            color: #000;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            user-select: none;
        }

        .quantity-display {
            color: #fff;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
            font-size: 1rem;
        }

        .cart-total {
            text-align: right;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .back-btn, .checkout-btn {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background: #1abc9c;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .back-btn:hover, .checkout-btn:hover {
            background: #169d86;
        }

        .remove-btn {
            padding: 6px 12px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            margin-left: 15px;
        }
    </style>
</head>
<body>

    <h1>Your Cart</h1>

    <div class="cart-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="cart-item">
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div class="cart-details">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p>RM <?= number_format($row['price'], 2) ?></p>

                        <div class="quantity-control">
                            <!-- Decrease quantity -->
                            <form action="update_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="cart_item_id" value="<?= $row['cart_item_id'] ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="quantity-btn" <?= ($row['quantity'] <= 1) ? 'disabled' : '' ?>>-</button>
                            </form>

                            <div class="quantity-display"><?= $row['quantity'] ?></div>

                            <!-- Increase quantity -->
                            <form action="update_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="cart_item_id" value="<?= $row['cart_item_id'] ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="quantity-btn">+</button>
                            </form>

                            <!-- Remove button -->
                            <form action="remove_from_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="cart_item_id" value="<?= $row['cart_item_id'] ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php $total += $row['price'] * $row['quantity']; ?>
            <?php endwhile; ?>

            <div class="cart-total">
                <strong>Total:</strong> RM <?= number_format($total, 2) ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #aaa;">Your cart is empty.</p>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="index.php" class="back-btn">Continue Shopping</a>

            <?php if ($result->num_rows > 0): ?>
                <form action="checkout.php" method="post" style="display: inline-block; margin-left: 10px;">
                    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
