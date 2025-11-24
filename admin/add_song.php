<?php
require '../config.php';
include '../navbar.php';

$message = "";

// Create folders if not exist (safe permissions)
if (!is_dir('uploads/audio'))
    mkdir('uploads/audio', 0755, true);
if (!is_dir('uploads/cover'))
    mkdir('uploads/cover', 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Basic input ---
    $title = trim($_POST['title']);
    $artist = trim($_POST['artist']);

    // --- Validate empty fields ---
    if ($title === "" || $artist === "") {
        $message = "<div class='alert alert-danger'>All fields are required!</div>";
    } else {

        // --- Validate audio file ---
        $allowedAudio = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'];
        if (!in_array($_FILES['audio']['type'], $allowedAudio)) {
            $message = "<div class='alert alert-danger'>Invalid audio file type!</div>";
        }

        // --- Validate image file ---
        $allowedImage = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['cover']['type'], $allowedImage)) {
            $message = "<div class='alert alert-danger'>Invalid image file type!</div>";
        }

        // --- Max size 10MB each ---
        if ($_FILES['audio']['size'] > 50 * 1024 * 1024) {
            $message = "<div class='alert alert-danger'>Audio file too large (max 50MB).</div>";
        }

        if ($_FILES['cover']['size'] > 5 * 1024 * 1024) {
            $message = "<div class='alert alert-danger'>Image too large (max 5MB).</div>";
        }

        // If no errors, continue
        if ($message === "") {

            // --- Safe randomized filenames ---
            $audioName = "audio_" . bin2hex(random_bytes(8)) . "_" . basename($_FILES['audio']['name']);
            $coverName = "cover_" . bin2hex(random_bytes(8)) . "_" . basename($_FILES['cover']['name']);

            $audioPath = "uploads/audio/$audioName";
            $coverPath = "uploads/cover/$coverName";

            // --- Move files ---
            if (!move_uploaded_file($_FILES['audio']['tmp_name'], $audioPath)) {
                $message = "<div class='alert alert-danger'>Failed to upload audio.</div>";
            }

            if (!move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath)) {
                $message = "<div class='alert alert-danger'>Failed to upload cover.</div>";
            }

            if ($message === "") {

                // --- SQL Safe Insert (Prepared Statement) ---
                $stmt = $conn->prepare("INSERT INTO songs (title, artist, audio_path, cover_path) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $title, $artist, $audioPath, $coverPath);

                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>Song added successfully!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Database error.</div>";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song - NOBILI Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5" style="max-width:600px;">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Add New Song</h2>

                <?= $message ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Song Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Artist Name</label>
                        <input type="text" name="artist" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Audio File</label>
                        <input type="file" name="audio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="cover" class="form-control" required>
                    </div>

                    <button class="btn btn-primary w-100">Add Song</button>
                </form>

            </div>
        </div>
    </div>

</body>

</html>