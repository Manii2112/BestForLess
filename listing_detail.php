<?php
include 'db.php';
$id = $_GET['id'];
$res = $conn->query("SELECT l.*, u.name, u.email FROM listings l JOIN users u ON l.user_id = u.id WHERE l.id = $id");
$row = $res->fetch_assoc();
?>

<h2><?= $row['title'] ?></h2>
<img src="uploads/<?= $row['image'] ?>" width="200"><br>
<p><strong>Price:</strong> RM <?= $row['price'] ?></p>
<p><?= $row['description'] ?></p>
<p><strong>Seller:</strong> <?= $row['name'] ?> (<?= $row['email'] ?>)</p>
<a href="index.php">← Back to Home</a>
