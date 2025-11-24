
<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NOBILI Music</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

    <?php include '../navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">NOBILI Admin Dashboard</h1>
        <p class="text-center">Welcome to your admin panel.</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h3><i class="bi bi-music-note-list"></i> Add Song</h3>
                    <a href="add_song.php" class="btn btn-primary mt-2">Add Now</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h3><i class="bi bi-collection-play"></i> Manage Songs</h3>
                    <a href="manage_songs.php" class="btn btn-dark mt-2">Go</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h3><i class="bi bi-play-circle"></i> Player</h3>
                    <a href="index.php" class="btn btn-success mt-2">Open Player</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
