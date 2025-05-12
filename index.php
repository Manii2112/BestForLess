<?php
include 'db.php';
session_start();

// Handle search query
$search = '';
if (isset($_GET['query'])) {
    $search = $conn->real_escape_string($_GET['query']);
    $res = $conn->query("SELECT * FROM listings WHERE title LIKE '%$search%' OR description LIKE '%$search%' ORDER BY created_at DESC");
} else {
    $res = $conn->query("SELECT * FROM listings ORDER BY created_at DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #121212;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e1e1e;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .logo h2 {
            margin: 0;
            color: #1abc9c;
        }

        .links .btn {
            margin-left: 15px;
            text-decoration: none;
            color: #1abc9c;
            font-weight: bold;
        }

        .container.hero {
            text-align: center;
            padding: 40px 20px 20px;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-title span {
            color: #1abc9c;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #ccc;
        }

        /* Search Bar */
        .search-form {
            display: flex;
            justify-content: center;
            margin: 20px auto;
            gap: 10px;
            padding: 0 20px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 1rem;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #1abc9c;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-form button:hover {
            background-color: #169d86;
        }

        .listing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 20px 40px;
        }

        .listing-card {
            background: #1e1e1e;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            transition: transform 0.2s;
        }

        .listing-card:hover {
            transform: translateY(-5px);
        }

        .listing-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .listing-info {
            padding: 10px 15px;
        }

        .listing-info h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #fff;
        }

        .listing-info p {
            margin: 8px 0 0;
            font-weight: bold;
            color: #1abc9c;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
            background: #1a1a1a;
            color: #aaa;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">
        <h2>BestForLess</h2>
    </div>
    <div class="links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="upload_listing.php" class="btn">Post New Listing</a>
            <a href="logout.php" class="btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn">Login</a>
            <a href="signup.php" class="btn">Sign Up</a>
        <?php endif; ?>
    </div>
</div>

<!-- Hero Section -->
<div class="container hero">
    <h1 class="hero-title">Welcome to <span>BestForLess</span></h1>
    <p class="hero-subtitle">Find great deals posted by MMU students — fast, simple, and local.</p>
</div>

<!-- Search Form -->
<form method="GET" action="index.php" class="search-form">
    <input type="text" name="query" placeholder="Search listings..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<!-- Listings Grid -->
<div class="listing-grid">
    <?php if ($res && $res->num_rows > 0): ?>
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="listing-card">
                <a href="listing_detail.php?id=<?= $row['id'] ?>" style="text-decoration: none; color: inherit;">
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div class="listing-info">
                        <h3><?= htmlspecialchars($row['title']) ?></h3>
                        <p>RM <?= number_format($row['price'], 2) ?></p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; color: #888;">No listings found.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer>
    &copy; 2025 BestForLess | Made for MMU Students ❤️
</footer>

</body>
</html>
