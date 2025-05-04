<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BestForLess</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Login</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="input-group">
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
            <label>Email</label>
        </div>
        <div class="input-group">
            <input type="password" name="password" required />
            <label>Password</label>
        </div>
        <button type="submit">Login</button>
    </form>

    <!-- Back Button -->
    <a href="index.php" class="back-button">Back to Home</a>

</div>

</body>
</html>
