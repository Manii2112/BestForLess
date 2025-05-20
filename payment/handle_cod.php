<?php
$conn = new mysqli("localhost", "root", "", "mmu_bestforless");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $listing_id = $_POST['listing_id'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    $sql = "INSERT INTO payments (listing_id, payment_method, payment_status, address, phone_number)
            VALUES (?, 'COD', 'COD Selected', ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $listing_id, $address, $phone_number);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ COD selected successfully.</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<link rel="stylesheet" href="style.css">
<h2>Select COD (Buyer)</h2>

<form method="post">
    <label for="listing_id">Listing ID:</label>
    <input type="number" name="listing_id" id="listing_id" required><br><br>

    <label for="address">Address:</label>
    <textarea name="address" id="address" rows="3" required></textarea><br><br>

    <label for="phone_number">Phone Number:</label>
    <input type="tel" name="phone_number" id="phone_number" pattern="[0-9]{10,15}" required><br><br>

    <button type="submit">Confirm COD</button>
</form>

