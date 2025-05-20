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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Base Styles */
        body {
            background: #121212;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar - Fixed Alignment */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e1e1e;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
            position: relative;
        }

        .logo h2 {
            margin: 0;
            color: #1abc9c;
            font-size: 1.5rem;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .login-btn {
            color: #1abc9c;
            border: 1px solid #1abc9c;
        }

        .login-btn:hover {
            background-color: rgba(26, 188, 156, 0.1);
        }

        .signup-btn {
            background-color: #1abc9c;
            color: white;
        }

        .signup-btn:hover {
            background-color: #169d86;
        }

        /* Hero Section */
        .container.hero {
            text-align: center;
            padding: 40px 20px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .hero-title span {
            color: #1abc9c;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #ccc;
            margin-bottom: 30px;
        }

        /* Search Bar */
        .search-form {
            display: flex;
            justify-content: center;
            margin: 20px auto;
            gap: 10px;
            padding: 0 20px;
            max-width: 600px;
        }

        .search-form input[type="text"] {
            padding: 10px 15px;
            width: 100%;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 1rem;
            background: #2d2d2d;
            color: white;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #1abc9c;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .search-form button:hover {
            background-color: #169d86;
        }

        /* Listings Grid */
        .listing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .listing-card {
            background: #1e1e1e;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .listing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.6);
        }

        .listing-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #333;
        }

        .listing-info {
            padding: 15px;
        }

        .listing-info h3 {
            margin: 0 0 8px;
            font-size: 1.1rem;
            color: #fff;
        }

        .listing-info p {
            margin: 0;
            font-weight: bold;
            color: #1abc9c;
            font-size: 1rem;
        }

        /* User Controls */
        .user-controls {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }

        .user-btn {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .post-btn {
            background-color: #1abc9c;
            color: white;
        }

        .post-btn:hover {
            background-color: #169d86;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 30px 20px;
            font-size: 0.9rem;
            background: #1a1a1a;
            color: #aaa;
            margin-top: 40px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .nav-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .listing-grid {
                grid-template-columns: 1fr;
                padding: 15px;
            }
            
            .user-controls {
                position: static;
                justify-content: center;
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>

<!-- User Controls (Top Right) -->
<?php if (isset($_SESSION['user_id'])): ?>
    <div class="user-controls">
        <a href="upload_listing.php" class="user-btn post-btn">Post Listing</a>
        <a href="logout.php" class="user-btn logout-btn">Logout</a>
    </div>
<?php endif; ?>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="logo">
        <h2>BestForLess</h2>
    </div>
    
    <div class="nav-buttons">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn login-btn">Login</a>
            <a href="signup.php" class="btn signup-btn">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

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

    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="add_to_cart.php" method="post" style="padding: 10px;">
            <input type="hidden" name="listing_id" value="<?= $row['id'] ?>">
            <button type="submit" style="
                width: 100%;
                padding: 10px;
                background-color: #1abc9c;
                color: white;
                border: none;
                border-radius: 8px;
                font-weight: bold;
                cursor: pointer;
                margin-top: 10px;
            ">Add to Cart</button>
        </form>
    <?php endif; ?>
</div>

        <?php endwhile; ?>
    <?php else: ?>
        <p style="grid-column: 1/-1; text-align: center; color: #888;">No listings found.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer>
    &copy; 2025 BestForLess | Made for MMU Students
</footer>

</body>
</html>