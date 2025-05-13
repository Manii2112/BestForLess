<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "mmu_bestforless");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $listing_id = $_POST['listing_id'];
    $seller_id = $_POST['seller_id'];

    $qr_img = $_FILES["qr_image"]["name"];
    $target_dir = "uploads/qr/";
    $target_file = $target_dir . basename($qr_img);

    move_uploaded_file($_FILES["qr_image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO payments (listing_id, seller_id, buyer_id, payment_method, qr_image, payment_status)
            VALUES ('$listing_id', '$seller_id', 0, 'QR', '$qr_img', 'Pending')";

    if ($conn->query($sql)) {
        echo "✅ QR uploaded successfully.";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<link rel="stylesheet" href="style.css">
<h2>Upload QR (Seller)</h2>
<form method="post" enctype="multipart/form-data">
    <label>Listing ID:</label>
    <input type="number" name="listing_id" required><br>
    <label>Seller ID:</label>
    <input type="number" name="seller_id" required><br>
    <label>QR Image:</label>
    <input type="file" name="qr_image" accept="image/*" required><br>
    <button type="submit">Upload</button>
</form>
