document.addEventListener('DOMContentLoaded', function() {
  const userModal = document.getElementById('userModal');
  const modalForm = document.getElementById('modal-user-form');
  const modalUserId = document.getElementById('modal-user-id');
  const modalUsername = document.getElementById('modal-username');
  const modalPassword = document.getElementById('modal-password');
  const modalRole = document.getElementById('modal-role');
  const modalTitle = document.getElementById('modal-title');
  const closeModalBtn = document.getElementById('closeModal');
  const modalPasswordLabel = document.getElementById('modal-password-label');

  window.resetForm = function() {
    modalTitle.textContent = 'Add User';
    modalForm.action = 'create_user.php';
    modalUserId.value = '';
    modalUsername.value = '';
    modalPassword.value = '';
    modalRole.value = '';
    modalPasswordLabel.textContent = 'Password';
    userModal.style.display = 'block';
  };

  window.editUser = function(id) {
    const row = document.querySelector(`tr[data-id='${id}']`);
    const username = row.getAttribute('data-username');
    const role = row.getAttribute('data-role');

    modalTitle.textContent = 'Edit User';
    modalForm.action = 'update_user.php';
    modalUserId.value = id;
    modalUsername.value = username;
    modalPassword.value = '';
    modalRole.value = role;
    modalPasswordLabel.textContent = 'Password';
    document.getElementById('password-note').style.display = 'block';
    userModal.style.display = 'block';
  };

  closeModalBtn.onclick = function() {
    userModal.style.display = 'none';
  };

  window.onclick = function(event) {
    if (event.target === userModal) {
      userModal.style.display = 'none';
    }
  };

  window.deleteUser = function(id) {
    if (confirm('Are you sure you want to delete this user?')) {
      window.location.href = `delete_user.php?id=${id}`;
    }
  };
});