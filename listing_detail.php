<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

// Fetch listing with seller info
$res = $conn->query("SELECT l.*, u.name, u.email FROM listings l JOIN users u ON l.user_id = u.id WHERE l.id = $id");
if ($res->num_rows === 0) {
    header('Location: index.php');
    exit;
}
$row = $res->fetch_assoc();

$message = '';
// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Check if item already in cart for this user
    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND listing_id = ?");
    $stmt->bind_param('ii', $user_id, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Item is already in your cart.";
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO cart (user_id, listing_id) VALUES (?, ?)");
        $stmt_insert->bind_param('ii', $user_id, $id);
        if ($stmt_insert->execute()) {
            $message = "Item added to cart successfully.";
        } else {
            $message = "Failed to add item to cart.";
        }
        $stmt_insert->close();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($row['title']) ?> - BestForLess</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 40px auto;
            gap: 30px;
            padding: 0 20px;
        }

        .left-section {
            flex: 2;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
        }

        .listing-image img {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 10px;
            background-color: #000;
        }

        .listing-title {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #00c896;
        }

        .price {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .details {
            margin-top: 30px;
        }

        .details h3 {
            margin-bottom: 10px;
            color: #ccc;
            font-size: 18px;
        }

        .details p {
            margin: 5px 0;
            font-size: 15px;
        }

        .right-section {
            flex: 1;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.4);
            max-height: fit-content;
        }

        .seller-box h4 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .seller-box p {
            font-size: 14px;
            color: #ccc;
        }

        .contact-button {
            display: inline-block;
            background-color: #00c896;
            color: #000;
            font-weight: bold;
            text-align: center;
            padding: 10px 20px;
            margin-top: 15px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .contact-button:hover {
            background-color: #00a37a;
        }

        .add-cart-btn {
            display: inline-block;
            background-color: #1abc9c;
            color: #000;
            font-weight: bold;
            text-align: center;
            padding: 12px 25px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .add-cart-btn:hover {
            background-color: #169d86;
        }

        a {
            color: #00c896;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #ccc;
            text-decoration: underline;
        }

        .message {
            background-color: #333;
            color: #1abc9c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left content -->
        <div class="left-section">
            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="listing-image">
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" />
            </div>
            <div class="listing-title"><?= htmlspecialchars($row['title']) ?></div>
            <div class="price">RM <?= number_format($row['price'], 2) ?></div>
            <div class="details">
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

                <h3>Details</h3>
                <p><strong>Condition:</strong> <?= htmlspecialchars($row['condition'] ?? 'Not specified') ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($row['category'] ?? 'Uncategorized') ?></p>
                <p><strong>Posted on:</strong> <?= date("F j, Y", strtotime($row['created_at'] ?? 'now')) ?></p>
            </div>

            <a class="back-link" href="index.php">&larr; Back to Home</a>
        </div>

        <!-- Right sidebar -->
        <div class="right-section">
            <div class="seller-box">
                <h4>Seller: <?= htmlspecialchars($row['name']) ?></h4>
                <p>Email: <a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></p>
                <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="contact-button">Contact Seller</a>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST" style="margin-top:20px;">
                    <button type="submit" class="add-cart-btn">Add to Cart</button>
                </form>
            <?php else: ?>
                <p style="margin-top:20px; color:#ccc;">Please <a href="login.php">login</a> to add to cart.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
