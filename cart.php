<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

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

        .cart-total {
            text-align: right;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background: #1abc9c;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .back-btn:hover {
            background: #169d86;
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

        <form action="remove_from_cart.php" method="post" style="margin-top: 10px;">
            <input type="hidden" name="cart_item_id" value="<?= $row['cart_item_id'] ?>">
            <button type="submit" style="
                padding: 6px 12px;
                background-color: #e74c3c;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 0.9rem;
                cursor: pointer;
            ">Remove</button>
        </form>
    </div>
</div>


                <?php $total += $row['price']; ?>
            <?php endwhile; ?>

            <div class="cart-total">
                <strong>Total:</strong> RM <?= number_format($total, 2) ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: #aaa;">Your cart is empty.</p>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="index.php" class="back-btn">Continue Shopping</a>
        </div>
    </div>

</body>
</html>
