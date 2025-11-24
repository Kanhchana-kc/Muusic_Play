// DOM elements
const audio = document.getElementById("audio");
const playBtn = document.getElementById("play");
const prevBtn = document.getElementById("prev");
const nextBtn = document.getElementById("next");
const titleEl = document.getElementById("title");
const artistEl = document.getElementById("artist");
const coverEl = document.getElementById("cover");
const progressContainer = document.getElementById("progress-container");
const progressEl = document.getElementById("progress");
const currentTimeEl = document.getElementById("current-time");
const durationEl = document.getElementById("duration");
const playlistListEl = document.getElementById("playlist-list");


// Songs list
const songs = [
  {
    title: "Song 1",
    artist: "Artist A",
    src: "songs/song1.mp3",
    cover: "images/cover1.jpg"
  },
  {
    title: "Song 2",
    artist: "Artist B",
    src: "songs/song2.mp3",
    cover: "images/cover2.jpg"
  },
  {
    title: "Song 3",
    artist: "Artist C",
    src: "songs/song3.mp3",
    cover: "images/cover3.jpg"
  },
  {
    title: "Song 4",
    artist: "Artist A",
    src: "songs/song4.mp3",
    cover: "images/cover4.jpg"
  },
  {
    title: "Song 2",
    artist: "Artist B",
    src: "songs/song5.mp3",
    cover: "images/cover5.jpg"
  },
  {
    title: "Song 6",
    artist: "Artist C",
    src: "songs/song6.mp3",
    cover: "images/cover6.jpg"
  },
  {
    title: "Song 2",
    artist: "Artist B",
    src: "songs/song7.mp3",
    cover: "images/cover7.jpg"
  },
//   {
//     title: "សម័យមីនី",
//     artist: "Artist C",
//     src: "songs/song7.mp3",
//     cover: "images/cover7.jpg"
//   },
];

let songIndex = 0;


// Load Song
function loadSong(song) {
  titleEl.innerText = song.title;
  artistEl.innerText = song.artist;
  coverEl.src = song.cover;
  audio.src = song.src;
}


// Play / Pause Button
playBtn.addEventListener("click", () => {
  if (audio.paused) {
    audio.play();
    playBtn.textContent = "⏸"; 
  } else {
    audio.pause();
    playBtn.textContent = "▶"; 
  }
});


// Previous Song
prevBtn.addEventListener("click", () => {
  songIndex--;
  if (songIndex < 0) songIndex = songs.length - 1;

  loadSong(songs[songIndex]);
  highlightPlaylist();
  audio.play();
  playBtn.textContent = "⏸";
});


// Next Song
nextBtn.addEventListener("click", () => {
  songIndex++;
  if (songIndex >= songs.length) songIndex = 0;

  loadSong(songs[songIndex]);
  highlightPlaylist();
  audio.play();
  playBtn.textContent = "⏸";
});


// Auto Next Song (IMPORTANT)
audio.addEventListener("ended", () => {
  songIndex++;
  if (songIndex >= songs.length) songIndex = 0;

  loadSong(songs[songIndex]);
  highlightPlaylist();
  audio.play();
  playBtn.textContent = "⏸";
});


// Update progress bar
audio.addEventListener("timeupdate", (e) => {
  const { currentTime, duration } = e.srcElement;

  if (duration) {
    progressEl.style.width = (currentTime / duration) * 100 + "%";
  }

  // Set current time
  let min = Math.floor(currentTime / 60);
  let sec = Math.floor(currentTime % 60).toString().padStart(2, "0");
  currentTimeEl.textContent = `${min}:${sec}`;

  // Set duration
  if (duration) {
    let dmin = Math.floor(duration / 60);
    let dsec = Math.floor(duration % 60).toString().padStart(2, "0");
    durationEl.textContent = `${dmin}:${dsec}`;
  }
});


// Click to seek
progressContainer.addEventListener("click", (e) => {
  const width = progressContainer.clientWidth;
  const clickX = e.offsetX;
  const duration = audio.duration;

  audio.currentTime = (clickX / width) * duration;
});


// Playlist
function loadPlaylist() {
  songs.forEach((song, index) => {
    const li = document.createElement("li");
    li.textContent = `${song.title} — ${song.artist}`;

    li.onclick = () => {
      songIndex = index;
      loadSong(songs[songIndex]);
      highlightPlaylist();
      audio.play();
      playBtn.textContent = "⏸";
    };

    playlistListEl.appendChild(li);
  });
}


// Highlight Active Song
function highlightPlaylist() {
  const items = playlistListEl.querySelectorAll("li");

  items.forEach((li, i) => {
    li.classList.toggle("active", i === songIndex);
  });
}


// Initialize
loadSong(songs[songIndex]);
loadPlaylist();
highlightPlaylist();
