<?php
$conn = new mysqli("localhost", "root", "", "mmu_bestforless");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $listing_id = $_POST['listing_id'];
    $buyer_id = $_POST['buyer_id'];
    $seller_id = $_POST['seller_id'];

    $sql = "INSERT INTO payments (listing_id, buyer_id, seller_id, payment_method, payment_status)
            VALUES ('$listing_id', '$buyer_id', '$seller_id', 'COD', 'COD Selected')";

    if ($conn->query($sql)) {
        echo "✅ COD selected successfully.";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="style.css">
<h2>Select COD (Buyer)</h2>
<form method="post">
    <label>Listing ID:</label>
    <input type="number" name="listing_id" required><br>
    <label>Buyer ID:</label>
    <input type="number" name="buyer_id" required><br>
    <label>Seller ID:</label>
    <input type="number" name="seller_id" required><br>
    <button type="submit">Choose COD</button>
</form>
