<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $pass);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post">
  <input name="name" placeholder="Full Name" required><br>
  <input name="email" placeholder="MMU Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">Sign Up</button>
</form>
