<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Check if password and confirm password match
    if ($pass !== $confirm_pass) {
        $error = "Passwords do not match!";
    } else {
        // Check if the email already exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error = "Email is already taken!";
        } else {
            // If email is not found, insert the new user
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $name, $email, $hashed_pass);
            $stmt->execute();

            $_SESSION['user_id'] = $stmt->insert_id;
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Signup</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="input-group">
            <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
            <label>Full Name</label>
        </div>
        <div class="input-group">
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
            <label>Email</label>
        </div>
        <div class="input-group">
            <input type="password" name="password" required />
            <label>Password</label>
        </div>
        <div class="input-group">
            <input type="password" name="confirm_password" required />
            <label>Confirm Password</label>
        </div>
        <button type="submit">Signup</button>
    </form>

    <!-- Back Button -->
    <a href="index.php" class="back-button">← Back to Home</a>

</div>

</body>
</html>
