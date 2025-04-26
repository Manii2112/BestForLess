<?php
include 'db.php';
session_start();

$res = $conn->query("SELECT * FROM listings ORDER BY created_at DESC");
?>

<h2>Welcome to BestForLess</h2>
<?php if (isset($_SESSION['user_id'])): ?>
  <a href="upload_listing.php">Post New Listing</a> |
  <a href="logout.php">Logout</a>
<?php else: ?>
  <a href="login.php">Login</a> | <a href="signup.php">Sign Up</a>
<?php endif; ?>

<hr>
<?php while ($row = $res->fetch_assoc()): ?>
  <div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <img src="uploads/<?= $row['image'] ?>" width="100">
    <h3><a href="listing_detail.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a></h3>
    <p>RM <?= $row['price'] ?></p>
  </div>
<?php endwhile; ?>
