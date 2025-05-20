<?php
include 'db.php';
$id = $_GET['id'];
$res = $conn->query("SELECT l.*, u.name, u.email FROM listings l JOIN users u ON l.user_id = u.id WHERE l.id = $id");
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['title']) ?> - BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
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

        a {
            color: #00c896;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #ccc;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left content -->
        <div class="left-section">
            <div class="listing-image">
                <img src="uploads/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['title']) ?>">
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
                <p>Email: <a href="mailto:<?= $row['email'] ?>"><?= $row['email'] ?></a></p>
                <a href="mailto:<?= $row['email'] ?>" class="contact-button">Contact Seller</a>
            </div>
        </div>
    </div>
</body>
</html>
