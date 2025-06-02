<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        // Redirect to main page after successful update
        header("Location: index.php");
        exit();
    } else {
        $message = "Error updating profile.";
    }
    $stmt->close();
} 

// Fetch current user data
$res = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$res->bind_param("i", $user_id);
$res->execute();
$result = $res->get_result();
$user = $result->fetch_assoc();
$res->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1abc9c;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #ccc;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background: #2d2d2d;
            color: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1abc9c;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #169d86;
        }

        .message {
            text-align: center;
            color: #e74c3c;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Profile</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>

        <button type="submit">Save Changes</button>
    </form>
</div>

</body>
</html>
