
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="admin.php">
            <i class="bi bi-speedometer2"></i> NOBILI Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="add_song.php">Add Song</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_songs.php">Manage Songs</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Go to Player</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/profile.php"> profile</a></li>

            </ul>
               <div>
            <span class="me-3">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
          <button class="btn btn-sm btn-outline-secondary" id="toggleTheme">Dark Mode</button>
        </div>

    </div>
</nav>
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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .cover-img {
            height: 180px;
            object-fit: cover;
            border-radius: .5rem;
        }

        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .song-card:hover .play-overlay {
            opacity: 1;
        }

        .sticky-player {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--card-bg);
            padding: 15px 20px;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 1000;
            flex-wrap: wrap;
            transition: background 0.3s, color 0.3s;
        }

        .sticky-player img {
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .sticky-player .info {
            flex: 1;
            min-width: 150px;
        }

        .sticky-player .controls button {
            margin: 0 5px;
            font-size: 18px;
            border: none;
            background: none;
            cursor: pointer;
            color: var(--primary-color);
        }

        .sticky-player .progress-container {
            flex-basis: 100%;
            height: 6px;
            background: #ddd;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        .sticky-player .progress-bar {
            height: 6px;
            width: 0%;
            background: var(--primary-color);
            border-radius: 5px;
            transition: width 0.2s;
        }

        .dark-mode {
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --text-color: #ffffff;
        }
    </style>