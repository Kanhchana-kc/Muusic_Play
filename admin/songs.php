<?php
require '../config.php';

$result = $conn->query("SELECT * FROM songs");
$songs = [];
while($row = $result->fetch_assoc()){
    $songs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($songs);
?>
