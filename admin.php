<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$current = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Product Management - Mia's Kape</title>
  <link rel="stylesheet" href="/Project_PosInventory/assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto&display=swap" rel="stylesheet">
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
        <h2>Welcome Admin!</h2>
      </section>
    </main>
  </div>
  <script>
    document.getElementById('adminButton').addEventListener('click', function() {
      document.getElementById('adminDropdown').classList.toggle('hidden');
    });
  </script>
</body>
</html>