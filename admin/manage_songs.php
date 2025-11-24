<?php
require '../config.php';
include '../navbar.php';

// Get songs
$songs = $conn->query("SELECT * FROM songs ORDER BY id DESC");
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Songs - NOBILI Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .action-buttons a {
            margin-right: 5px;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center fw-bold">🎵 Manage Songs</h2>

        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Audio</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $songs->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td><?= $row['id'] ?></td>
                            <td>
                                <?php if (!empty($row['cover_path'])): ?>
                                    <img src="<?= $row['cover_path'] ?>" alt="cover">
                                <?php else: ?>
                                    <span class="text-muted">No cover</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['title'] ?></td>
                            <td><?= $row['artist'] ?></td>
                            <td>
                                <?php if (!empty($row['audio_path'])): ?>
                                    <audio controls>
                                        <source src="<?= $row['audio_path'] ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <?php else: ?>
                                    <span class="text-muted">No audio</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <!-- Edit button -->
                                <a href="edit_song.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="bi-pencil-fill"></i>
                                </a>

                                <!-- Delete button -->
                                <a href="delete_song.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this song?');">
                                    <i class="bi-trash-fill"></i>
                                </a>

                                <!-- Optional Update button -->
                                <!-- <a href="update_song.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">
                        <i class="bi-arrow-clockwise"></i>
                    </a> -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>