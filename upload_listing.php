<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp, "uploads/$image");

    $query = "INSERT INTO listings (user_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issds", $_SESSION['user_id'], $title, $desc, $price, $image);
    $stmt->execute();
    header("Location: index.php");
}
?>

<form method="post" enctype="multipart/form-data">
  <input name="title" placeholder="Item Title" required><br>
  <input name="price" placeholder="Price" type="number" step="0.01" required><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <input type="file" name="image" required><br>
  <button type="submit">Post Listing</button>
</form>
