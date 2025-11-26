<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$menuItems = [];

if ($isAdmin) {
    $menuItems = [
        ['name' => 'Dashboard', 'link' => '/php/song_playList/admin/admin.php'],
        ['name' => 'Add Song', 'link' => '/php/song_playList/admin/add_song.php'],
        ['name' => 'Manage Songs', 'link' => '/php/song_playList/admin/manage_songs.php'],
        ['name' => 'Manage Users', 'link' => '/php/song_playList/admin/manage_users.php'],
        ['name' => 'Profile', 'link' => '/php/song_playList/admin/profile.php']
    ];
} elseif (isset($_SESSION['role'])) {
    $menuItems = [
        ['name' => 'Player', 'link' => '/php/song_playList/index.php'],
        ['name' => 'Profile', 'link' => '/php/song_playList/user/profile.php']
    ];
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $isAdmin ? '/php/song_playList/admin/admin.php' : '/php/song_playList/index.php' ?>">
            <i class="bi bi-music-note-beamed"></i> NOBILI <?= $isAdmin ? 'Admin' : 'Music' ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php if (!empty($menuItems)): ?>
                <?php foreach ($menuItems as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $item['link'] ?>"><?= $item['name'] ?></a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="/php/song_playList/auth/login.php">Login</a></li>
            <?php endif; ?>
        </ul>

        <?php if (isset($_SESSION['role'])): ?>
            <div class="d-flex gap-2 align-items-center">
                <button class="btn btn-sm btn-outline-secondary" id="toggleTheme">Dark Mode</button>
                <a href="/php/song_playList/auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</div>


</nav>
