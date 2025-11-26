<?php
session_start();
require '../config.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$error = '';
$success = '';

// Fetch current admin info
$admin_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $admin = $result->fetch_assoc();
} else {
    $error = "User not found.";
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $update->bind_param("si", $hashed, $admin_id);
        if ($update->execute()) {
            header("Location: profile.php?success=1");
            exit;
        } else {
            $error = "Failed to update password.";
        }
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Password updated successfully!";
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - NOBILI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="card mx-auto shadow-sm" style="max-width: 500px;">
        <div class="card-header">
            <h4 class="mb-0">Admin Profile</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
        <p><strong>Username:</strong> <?= htmlspecialchars($admin['username']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($admin['role']) ?></p>

        <hr>
        <h5>Change Password</h5>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Update Password</button>
        </form>
        <a href="admin.php" class="btn btn-secondary w-100 mt-3">Back to Dashboard</a>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
