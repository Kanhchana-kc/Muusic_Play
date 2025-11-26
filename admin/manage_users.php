<?php
session_start();
require '../config.php';

// Only admin can access
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../login.php");
//     exit;
// }

// DELETE USER
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: manage_users.php");
    exit;
}

// ADD USER
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

// EDIT USER
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

// FETCH USERS
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h2 class="mb-4">Manage Users</h2>

    <!-- USER LIST -->
    <table class="table table-bordered table-striped">
        <thead style="background:#222;color:white;">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= ucfirst($row['username']) ?></td>
                    <td><?= ucfirst($row['role']) ?></td>
                    <td>
                        <!-- EDIT BUTTON -->
                        <button class="btn btn-warning btn-sm"
                                onclick="editUser(<?= $row['id'] ?>, '<?= $row['username'] ?>', '<?= $row['role'] ?>')">
                            Edit
                        </button>

                        <!-- DELETE BUTTON -->
                        <a href="manage_users.php?delete=<?= $row['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this user?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <!-- ADD USER FORM -->
    <h3 class="mt-5">Add User</h3>

    <form method="POST" class="border p-4 bg-white rounded">
        <input type="hidden" name="add_user">

        <div class="mb-3">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button class="btn btn-success">Add User</button>
    </form>


    <!-- EDIT POPUP FORM -->
    <div id="editForm" class="border p-4 bg-white rounded mt-5" style="display:none;">
        <h3>Edit User</h3>

        <form method="POST">
            <input type="hidden" name="edit_user">
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="username" id="edit_username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>New Password (optional):</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Role:</label>
                <select name="role" id="edit_role" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" onclick="hideEdit()">Cancel</button>
        </form>
    </div>

</div>


<script>
function editUser(id, username, role) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
}

function hideEdit() {
    document.getElementById('editForm').style.display = 'none';
}
</script>

</body>
</html>
