
<?php
require '../config.php';
include '../navbar.php';

$id = $_GET['id'];

// Fetch song info
$song = $conn->query("SELECT * FROM songs WHERE id = $id")->fetch_assoc();

if (!$song) {
    die("Song not found!");
}

$message = "";

// Update when form submitted
if (isset($_POST['update'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $artist = $conn->real_escape_string($_POST['artist']);

    $audioPath = $song['audio_path'];
    $coverPath = $song['cover_path'];

    // New audio uploaded?
    if (!empty($_FILES['audio']['name'])) {
        $audioPath = "uploads/audio/" . time() . "_" . $_FILES['audio']['name'];
        move_uploaded_file($_FILES['audio']['tmp_name'], $audioPath);
    }

    // New cover uploaded?
    if (!empty($_FILES['cover']['name'])) {
        $coverPath = "uploads/cover/" . time() . "_" . $_FILES['cover']['name'];
        move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath);
    }

    // Update database
    $sql = "UPDATE songs SET 
            title='$title', 
            artist='$artist', 
            audio_path='$audioPath', 
            cover_path='$coverPath'
            WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success'>Song updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Failed to update song.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Song</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5" style="max-width:600px;">
        <div class="card shadow">
            <div class="card-body">

                <h2 class="text-center mb-4">Edit Song</h2>

                <?= $message ?>

                <form method="post" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="<?= $song['title'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Artist</label>
                        <input type="text" name="artist" class="form-control" value="<?= $song['artist'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Current Cover:</label><br>
                        <img src="<?= $song['cover_path'] ?>" width="120">
                        <input type="file" name="cover" class="form-control mt-2">
                    </div>

                    <div class="mb-3">
                        <label>Current Audio:</label><br>
                        <audio controls style="width:200px;">
                            <source src="<?= $song['audio_path'] ?>">
                        </audio>
                        <input type="file" name="audio" class="form-control mt-2">
                    </div>

                    <button type="submit" name="update" class="btn btn-primary w-100">Update Song</button>

                </form>

            </div>
        </div>
    </div>

</body>

</html>
