<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($name, $email, $phone, $address);
$query->fetch();
$query->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-container {
            max-width: 700px;
            margin: 60px auto;
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.6);
            color: white;
        }

        .profile-container h2 {
            color: #1abc9c;
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-detail {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .profile-label {
            color: #aaa;
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .edit-btn {
            display: block;
            margin: 30px auto 0;
            padding: 10px 20px;
            background-color: #1abc9c;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .edit-btn:hover {
            background-color: #169d86;
        }
    </style>
</head>
<body>

<div class="user-controls">
    <a href="index.php" class="user-btn cart-btn">Home</a>
    <a href="cart.php" class="user-btn cart-btn">View Cart</a>
    <a href="upload_listing.php" class="user-btn post-btn">Post Listing</a>
    <a href="logout.php" class="user-btn logout-btn">Logout</a>
</div>

<div class="profile-container">
    <h2>Your Profile</h2>

    <div class="profile-detail"><span class="profile-label">Name:</span> <?= htmlspecialchars($name) ?></div>
    <div class="profile-detail"><span class="profile-label">Email:</span> <?= htmlspecialchars($email) ?></div>
    <div class="profile-detail"><span class="profile-label">Phone:</span> <?= htmlspecialchars($phone) ?></div>
    <div class="profile-detail"><span class="profile-label">Address:</span> <?= nl2br(htmlspecialchars($address)) ?></div>

    <!-- Edit Profile button -->
    <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
</div>

</body>
</html>
