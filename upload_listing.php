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
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post Listing</title>
  <link rel="stylesheet" href="css/style.css"> <!-- Ensure this matches your login CSS -->
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .form-container {
      background-color: white;
      padding: 40px 30px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group input,
    .input-group textarea {
      width: 100%;
      padding: 10px 10px 10px 0;
      border: none;
      border-bottom: 2px solid #ccc;
      background: transparent;
      outline: none;
      font-size: 16px;
    }

    .input-group label {
      position: absolute;
      top: 10px;
      left: 0;
      color: #aaa;
      pointer-events: none;
      transition: 0.2s ease all;
    }

    .input-group input:focus ~ label,
    .input-group input:valid ~ label,
    .input-group textarea:focus ~ label,
    .input-group textarea:valid ~ label {
      top: -15px;
      font-size: 12px;
      color: #2196F3;
    }

    button {
      background-color: #2196F3;
      color: white;
      border: none;
      padding: 12px 18px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0b7dda;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Post a Listing</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="input-group">
      <input type="text" name="title" required>
      <label>Item Title</label>
    </div>
    <div class="input-group">
      <input type="number" name="price" step="0.01" required>
      <label>Price</label>
    </div>
    <div class="input-group">
      <textarea name="description" rows="4" required></textarea>
      <label>Description</label>
    </div>
    <div class="input-group">
      <input type="file" name="image" required>
    </div>
    <button type="submit">Post Listing</button>
  </form>
</div>

</body>
</html>
