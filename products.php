<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$current = basename($_SERVER['PHP_SELF']);

// Connect to DB
$mysqli = new mysqli("localhost", "root", "", "project01");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Fetch all products
$result = $mysqli->query("SELECT id, name, category, price, stock, image FROM products ORDER BY id ASC");
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Products - Mia's Kape</title>
  <link rel="stylesheet" href="/Project_PosInventory/assets/style.css">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <h2 class="brand">Mia's Kape</h2>
      <nav class="nav-links">
        <a href="admin.php" class="<?= $current === 'admin.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="user_management.php" class="<?= $current === 'user_management.php' ? 'active' : '' ?>">Users</a>
        <a href="products.php" class="<?= $current === 'products.php' ? 'active' : '' ?>">Products</a>
        <a href="purchases.php" class="<?= $current === 'purchases.php' ? 'active' : '' ?>">Purchases</a>
        <a href="sales.php" class="<?= $current === 'sales.php' ? 'active' : '' ?>">Sales</a>
        <a href="returns.php" class="<?= $current === 'returns.php' ? 'active' : '' ?>">Returns</a>
      </nav>
    </aside>
    <main class="main-content">
      <header class="topbar">
        <div class="admin-actions">
          <button id="adminButton"><?= htmlspecialchars($username) ?> ▾</button>
          <div class="dropdown hidden" id="adminDropdown">
            <a href="logout.php">Logout</a>
          </div>
        </div>
      </header>
      <section class="content">
        <div class="products-header">
          <h2>Products</h2>
          <button class="btn-add" onclick="resetProductForm()">Add Product</button>
        </div>
        <table class="products-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Category</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
              <tr
                data-id="<?= $product['id'] ?>"
                data-name="<?= htmlspecialchars($product['name']) ?>"
                data-price="<?= $product['price'] ?>"
                data-stock="<?= $product['stock'] ?>"
                data-category="<?= htmlspecialchars($product['category']) ?>"
                data-image="<?= htmlspecialchars($product['image']) ?>"
              >
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>₱<?= number_format($product['price'], 2) ?></td>
                <td><?= $product['stock'] ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td>
                  <?php if (!empty($product['image'])): ?>
                    <img src="./assets/images/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                  <?php else: ?>
                    <span style="color:#bbb;">No image</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn-edit" onclick="editProduct(<?= $product['id'] ?>)">Edit</button>
                  <button class="btn-delete" onclick="deleteProduct(<?= $product['id'] ?>)">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Product Modal -->
        <div id="productModal" class="modal">
          <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3 id="modal-title">Add Product</h3>
            <form id="modal-product-form" method="POST" action="create_product.php" enctype="multipart/form-data">
              <input type="hidden" name="product_id" id="modal-product-id" value="" />
              <div class="form-group">
                <label for="modal-product-name">Name</label>
                <input type="text" id="modal-product-name" name="product_name" required />
              </div>
              <div class="form-group">
                <label for="modal-product-price">Price</label>
                <input type="number" id="modal-product-price" name="product_price" min="0" step="0.01" required />
              </div>
              <div class="form-group">
                <label for="modal-product-stock">Stock</label>
                <input type="number" id="modal-product-stock" name="product_stock" min="0" required />
              </div>
              <div class="form-group">
                <label for="modal-product-category">Category</label>
                <select id="modal-product-category" name="product_category" required>
                     <option value="" disabled selected>Select category</option>
                     <option value="Coffee">Coffee</option>
                     <option value="Tea">Tea</option>
                     <option value="Dessert">Dessert</option>
                     <option value="Non-Coffee">Non-Coffee</option>
                </select>
              </div>
              <div class="form-group">
                <label for="modal-product-image">Image</label>
                <input type="file" id="modal-product-image" name="product_image" accept="image/*" />
                <img id="image-preview" src="" alt="Preview" style="display:none;width:80px;height:80px;margin-top:8px;object-fit:cover;border-radius:6px;">
              </div>
              <div class="modal-form-buttons">
             <input type="submit" value="Save" />
             <button type="button" onclick="document.getElementById('productModal').style.display='none'">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>
   <script>
    document.getElementById('adminButton').addEventListener('click', function() {
      document.getElementById('adminDropdown').classList.toggle('hidden');
    });
  </script>
  <script src="/Project_PosInventory/assets/js/product.js"></script>
</body>
</html>