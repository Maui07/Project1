<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $stock = $_POST['product_stock'];
    $category = $_POST['product_category'];

    $imageName = '';
    if (!empty($_FILES['product_image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['product_image']['name']);
        $targetPath = './assets/images/' . $imageName;

        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $targetPath)) {
            die('Failed to upload image.');
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, category, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiss", $name, $price, $stock, $category, $imageName);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
