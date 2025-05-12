<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$successMessage = '';
$errorMessage = '';

// Handle search bar input (optional future use)
$searchQuery = '';
if (isset($_GET['query'])) {
    $searchQuery = htmlspecialchars($_GET['query']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $desc = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $imageType = pathinfo($image, PATHINFO_EXTENSION);
    
    // Validate image type and size
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($imageType, $allowedTypes)) {
        $errorMessage = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
    } elseif ($_FILES['image']['size'] > $maxSize) {
        $errorMessage = 'Image size must be less than 2MB.';
    } else {
        // Move the image to the uploads folder
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($image);
        if (move_uploaded_file($tmp, $targetFile)) {
            // Insert into the database
            $query = "INSERT INTO listings (user_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("issds", $_SESSION['user_id'], $title, $desc, $price, $image);
            if ($stmt->execute()) {
                $successMessage = 'Your listing has been posted successfully!';
                header("Location: index.php"); // Redirect after posting
                exit;
            } else {
                $errorMessage = 'There was an error posting your listing.';
            }
        } else {
            $errorMessage = 'Failed to upload the image.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post Listing</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: rgb(238, 234, 234);
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
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
      margin-bottom: 20px;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #00BCD4;
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
      color: #000;
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
      background-color: #00BCD4;
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
      background-color: #00a3ba;
    }

    .message {
      text-align: center;
      padding: 10px;
      margin-bottom: 20px;
      font-size: 16px;
    }

    .success {
      background-color: #4CAF50;
      color: white;
    }

    .error {
      background-color: #f44336;
      color: white;
    }

    .search-container {
      width: 100%;
      max-width: 400px;
    }

    .search-container form {
      display: flex;
      gap: 10px;
    }

    .search-container input[type="text"] {
      flex: 1;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .search-container button {
      padding: 10px 15px;
      background-color: #00BCD4;
      color: white;
      border: none;
      border-radius: 4px;
    }

    .search-container button:hover {
      background-color: #00a3ba;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Post a Listing</h2>
  
  <?php if ($successMessage): ?>
    <div class="message success"><?= $successMessage; ?></div>
  <?php elseif ($errorMessage): ?>
    <div class="message error"><?= $errorMessage; ?></div>
  <?php endif; ?>
  
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

<div class="search-container">
  <form method="get">
    <input type="text" name="query" placeholder="Search listings..." value="<?= $searchQuery ?>">
    <button type="submit">Search</button>
  </form>
</div>

</body>
</html>
