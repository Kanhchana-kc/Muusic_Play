
<?php
require '../config.php';

$id = $_GET['id'];

// Get song paths
$song = $conn->query("SELECT * FROM songs WHERE id=$id")->fetch_assoc();

if ($song) {
    // Delete files
    if (file_exists($song['audio_path'])) unlink($song['audio_path']);
    if (file_exists($song['cover_path'])) unlink($song['cover_path']);

    // Delete from DB
    $conn->query("DELETE FROM songs WHERE id=$id");
}

header("Location: manage_songs.php");
exit();
?>
