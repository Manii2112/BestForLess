<<?php
$conn = new mysqli("localhost", "root", "", "mmu_bestforless");
$payment_id = $_GET["id"] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_qr"])) {
    $qr_img = $_FILES["qr_image"]["name"];
    $target_dir = "uploads/qr/";
    $target_file = $target_dir . basename($qr_img);

    // Basic validation for image file type (optional but recommended)
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_ext = strtolower(pathinfo($qr_img, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_types)) {
        echo "❌ Only JPG, JPEG, PNG & GIF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["qr_image"]["tmp_name"], $target_file)) {
            $sql = "UPDATE payments SET qr_image='$qr_img' WHERE id='$payment_id'";
            if ($conn->query($sql)) {
                echo "✅ QR image uploaded successfully.";
            } else {
                echo "❌ Database update failed: " . $conn->error;
            }
        } else {
            echo "❌ Failed to upload QR image.";
        }
    }
}

// Fetch payment info for display (optional)
$result = $conn->query("SELECT * FROM payments WHERE id = '$payment_id'");
$row = $result->fetch_assoc();
?>

<h2>Upload QR Image</h2>

<?php if ($row): ?>
    <form method="post" enctype="multipart/form-data">
        <label>Select QR Image:</label>
        <input type="file" name="qr_image" accept="image/*" required><br><br>
        <button type="submit" name="upload_qr">Upload QR</button>
    </form>

    <?php if ($row['qr_image']): ?>
        <p>Current QR Image:</p>
        <img src="uploads/qr/<?= htmlspecialchars($row['qr_image']) ?>" width="200">
    <?php endif; ?>
<?php else: ?>
    <p>Invalid payment ID.</p>
<?php endif; ?>
