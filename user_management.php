<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
$current = basename($_SERVER['PHP_SELF']);

// Connect to DB (update with your credentials)
$mysqli = new mysqli("localhost", "root", "", "project01");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Fetch all users
$result = $mysqli->query("SELECT id, username, role FROM users ORDER BY id ASC");
$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Management - Mia's Kape</title>
  <link rel="stylesheet" href="/Project_PosInventory/assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <h2 class="brand"> Mia's Kape</h2>
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
        <h2>User Management</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
            <?php foreach ($users as $user): ?>
              <tr data-id="<?= $user['id'] ?>" data-username="<?= htmlspecialchars($user['username']) ?>" data-role="<?= $user['role'] ?>">
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                  <button class="btn-add" onclick="resetForm()">Add</button>
                  <button class="btn-edit" onclick="editUser(<?= $user['id'] ?>)">Edit</button>
                  <button class="btn-delete" onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

<div id="userModal" class="modal">
  <div class="modal-content">
    <span class="close" id="closeModal">&times;</span>
    <h3 id="modal-title">Add User</h3>
    <form id="modal-user-form" method="POST" action="create_user.php">
      <input type="hidden" name="id" id="modal-user-id" value="" />
      <div class="form-group">
        <label for="modal-username">Username</label>
        <input type="text" id="modal-username" name="username" required />
      </div>
      <div class="form-group">
        <label for="modal-password" id="modal-password-label">Password</label>
        <input type="password" id="modal-password" name="password" required />
        <span id="password-note" class="password-note" style="display:none;">(leave blank to keep current password)</span>
      </div>
      <div class="form-group">
        <label for="modal-role">Role</label>
        <select id="modal-role" name="role" required>
          <option value="" disabled selected>Select Role</option>
          <option value="admin">Admin</option>
          <option value="cashier">Cashier</option>
        </select>
      </div>
      <input type="submit" value="Add User" />
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
  <script src="./assets/js/user_management.js"></script>
</body>
</html>