<?php
require '../config.php';
include '../navbar.php';

// Fetch songs
$songs = $conn->query("SELECT * FROM songs ORDER BY id ASC");
$songList = [];
while ($row = $songs->fetch_assoc()) {
    $songList[] = [
        'title' => $row['title'],
        'artist' => $row['artist'],
        'audio' => $row['audio_path'],
        'cover' => $row['cover_path']
    ];
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NOBILI Music Player</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root {
    --bg-color: #ffffff;
    --card-bg: #fff;
    --text-color: #000;
    --primary-color: #0d6efd;
}

body {
    background: var(--bg-color);
    color: var(--text-color);
    font-family: 'Segoe UI', sans-serif;
    margin-bottom: 100px;
    transition: background 0.3s, color 0.3s;
}

.song-card {
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s, background 0.3s, color 0.3s;
    position: relative;
    overflow: hidden;
    background: var(--card-bg);
    color: var(--text-color);
}
.song-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}

.cover-img {
    height: 180px;
    object-fit: cover;
    border-radius: .5rem;
}

.play-overlay {
    position: absolute;
    top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.4);
    color:#fff;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:40px;
    opacity:0;
    transition: opacity 0.3s;
}
.song-card:hover .play-overlay {
    opacity:1;
}

.sticky-player {
    position: fixed;
    bottom:0;
    left:0;
    width:100%;
    background: var(--card-bg);
    padding:15px 20px;
    box-shadow: 0 -5px 15px rgba(0,0,0,0.2);
    display:flex;
    align-items:center;
    gap:15px;
    z-index:1000;
    flex-wrap: wrap;
    transition: background 0.3s, color 0.3s;
}

.sticky-player img {
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
}

.sticky-player .info {
    flex:1;
    min-width:150px;
}

.sticky-player .controls button {
    margin: 0 5px;
    font-size:18px;
    border:none;
    background:none;
    cursor:pointer;
    color: var(--primary-color);
}

.sticky-player .progress-container {
    flex-basis:100%;
    height:6px;
    background:#ddd;
    border-radius:5px;
    cursor:pointer;
    margin-top:5px;
}

.sticky-player .progress-bar {
    height:6px;
    width:0%;
    background: var(--primary-color);
    border-radius:5px;
    transition: width 0.2s;
}

.dark-mode {
    --bg-color: #121212;
    --card-bg: #1e1e1e;
    --text-color: #ffffff;
}
</style>

</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">🎵 NOBILI Music Player</h2>
        <button class="btn btn-sm btn-outline-secondary" id="toggleTheme">Dark Mode</button>
    </div>

<div class="row row-cols-1 row-cols-md-4 g-4" id="songContainer">
    <?php foreach ($songList as $index => $song): ?>
    <div class="col">
        <div class="card song-card shadow-sm" onclick="playSong(<?= $index ?>)">
            <img src="<?= $song['cover'] ?>" class="cover-img card-img-top">
            <div class="play-overlay"><i class="bi-play-fill"></i></div>
            <div class="card-body text-center">
                <h5 class="card-title"><?= $song['title'] ?></h5>
                <p class="text-muted mb-0"><?= $song['artist'] ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</div>

<div class="sticky-player" id="playerContainer">
    <img id="nowPlayingCover" src="" alt="cover">
    <div class="info">
        <h6 id="nowPlayingTitle" class="mb-0">Select a song</h6>
        <small id="nowPlayingArtist" class="text-muted">Artist</small>
        <div class="progress-container mt-2" onclick="setProgress(event)">
            <div class="progress-bar" id="progressBar"></div>
        </div>
    </div>
    <div class="controls d-flex align-items-center gap-1">
        <button onclick="prevSong()"><i class="bi-skip-start-fill"></i></button>
        <button onclick="togglePlayPause()" id="playPauseBtn"><i class="bi-play-fill"></i></button>
        <button onclick="stopSong()"><i class="bi-stop-fill"></i></button>
        <button onclick="nextSong()"><i class="bi-skip-end-fill"></i></button>
        <button onclick="shuffleSong()"><i class="bi-shuffle"></i></button>
        <button onclick="repeatSong()"><i class="bi-arrow-repeat"></i></button>
        <input type="range" id="volumeControl" min="0" max="1" step="0.01" value="1" class="form-range w-25">
    </div>
</div>

<audio id="audioPlayer" preload="metadata"></audio>

<script>
const songs = <?= json_encode($songList) ?>;
let currentIndex = -1;
let isShuffle = false;
let isRepeat = false;

const audio = document.getElementById('audioPlayer');
const titleEl = document.getElementById('nowPlayingTitle');
const artistEl = document.getElementById('nowPlayingArtist');
const coverEl = document.getElementById('nowPlayingCover');
const progressBar = document.getElementById('progressBar');
const volumeControl = document.getElementById('volumeControl');
const body = document.body;
const playPauseBtn = document.getElementById('playPauseBtn');
const toggleBtn = document.getElementById('toggleTheme');

function playSong(index) {
    currentIndex = index;
    audio.src = songs[index].audio;
    audio.play();
    updatePlayerUI();
    playPauseBtn.innerHTML = '<i class="bi-pause-fill"></i>';
}

function updatePlayerUI() {
    if (currentIndex >= 0) {
        titleEl.textContent = songs[currentIndex].title;
        artistEl.textContent = songs[currentIndex].artist;
        coverEl.src = songs[currentIndex].cover;
    }
}

function togglePlayPause() {
    if (!audio.src) return;
    if (audio.paused) {
        audio.play();
        playPauseBtn.innerHTML = '<i class="bi-pause-fill"></i>';
    } else {
        audio.pause();
        playPauseBtn.innerHTML = '<i class="bi-play-fill"></i>';
    }
}

function stopSong() {
    audio.pause();
    audio.currentTime = 0;
    playPauseBtn.innerHTML = '<i class="bi-play-fill"></i>';
}

function nextSong() {
    if (isShuffle) {
        playSong(Math.floor(Math.random() * songs.length));
    } else if (currentIndex + 1 < songs.length) {
        playSong(currentIndex + 1);
    } else if (isRepeat) {
        playSong(0);
    }
}

function prevSong() {
    if (currentIndex - 1 >= 0) playSong(currentIndex - 1);
}

function shuffleSong() {
    isShuffle = !isShuffle;
    alert('Shuffle ' + (isShuffle ? 'ON' : 'OFF'));
}

function repeatSong() {
    isRepeat = !isRepeat;
    alert('Repeat ' + (isRepeat ? 'ON' : 'OFF'));
}

audio.addEventListener('timeupdate', () => {
    if(audio.duration){
        const percent = (audio.currentTime / audio.duration) * 100;
        progressBar.style.width = percent + '%';
    }
});

audio.addEventListener('ended', nextSong);

function setProgress(e) {
    const width = e.currentTarget.clientWidth;
    const clickX = e.offsetX;
    audio.currentTime = (clickX / width) * audio.duration;
}

volumeControl.addEventListener('input', () => {
    audio.volume = volumeControl.value;
});

toggleBtn.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    toggleBtn.textContent = body.classList.contains('dark-mode') ? 'Light Mode' : 'Dark Mode';
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
