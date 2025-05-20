<?php
$conn = new mysqli("localhost", "root", "", "mmu_bestforless");
$payment_id = $_GET["id"] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pay"])) {
    $receipt_img = $_FILES["receipt_image"]["name"];
    $target_dir = "uploads/receipts/";
    $target_file = $target_dir . basename($receipt_img);

    $address = $conn->real_escape_string($_POST["address"]);
    $phone = $conn->real_escape_string($_POST["phone"]);

    move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $target_file);

    $sql = "UPDATE payments SET receipt_image='$receipt_img', payment_status='Paid', address='$address', phone_number='$phone' WHERE id='$payment_id'";
    $conn->query($sql);
    echo "✅ Receipt uploaded.";
}

$result = $conn->query("SELECT * FROM payments WHERE id = '$payment_id'");
$row = $result->fetch_assoc();
?>

<link rel="stylesheet" href="style.css">
<h2>Make Payment (Buyer)</h2>

<?php if ($row): ?>
    <img src="uploads/qr/<?= htmlspecialchars($row['qr_image']) ?>" width="200"><br>
    <form method="post" enctype="multipart/form-data">
        <label>Address:</label>
        <input type="text" name="address" required><br>

        <label>Phone Number:</label>
        <input type="text" name="phone" pattern="[0-9]{10,15}" required><br>

        <label>Receipt Image:</label>
        <input type="file" name="receipt_image" accept="image/*" required><br>

        <button type="submit" name="pay">Submit Receipt</button>
    </form>
<?php else: ?>
    <p>Invalid payment ID.</p>
<?php endif; ?>

