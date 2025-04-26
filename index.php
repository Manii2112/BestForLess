<?php
include 'db.php';
session_start();

// Fetch listings from database
$res = $conn->query("SELECT * FROM listings ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>BestForLess</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">
        <h2>BestForLess</h2>
    </div>
    <div class="links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="upload_listing.php">Post New Listing</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <h1>Welcome to BestForLess</h1>

    <div class="listings">
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="listing">
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                <h2><a href="listing_detail.php?id=<?= $row['id'] ?>" style="color: #00bcd4; text-decoration: none;"><?= htmlspecialchars($row['title']) ?></a></h2>
                <p>RM <?= htmlspecialchars($row['price']) ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer>
    &copy; 2025 BestForLess | Made for MMU Students ❤️
</footer>

</body>
</html>
