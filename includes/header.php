<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = "http://" . $_SERVER['SERVER_NAME'] . "/uas_webservice";

// Proteksi Halaman
if (!isset($_SESSION['login'])) {
    header("Location: " . $base_url . "/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Resep Digital Anda</title>
    <!-- Premium Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>/assets/css/style.css?v=<?= time() ?>">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
</head>
<body>

<nav class="navbar">
    <div class="container nav-container">
        <a href="<?= $base_url ?>/index.php" class="nav-logo">
            <div style="background: var(--c-primary); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.2rem;">
                <i class="fa-solid fa-utensils"></i>
            </div>
            Recipe<span>Hub</span>
        </a>
        <ul class="nav-menu">
            <li><a href="<?= $base_url ?>/index.php">Beranda</a></li>
            <li><a href="<?= $base_url ?>/resep/index.php">Katalog Resep</a></li>
            <li><a href="<?= $base_url ?>/api/index.php">Jelajahi Dunia</a></li>
        </ul>
        <div class="nav-cta" style="display: flex; gap: 10px; align-items: center;">
            <button id="theme-toggle" class="btn btn-outline" style="padding: 0.6rem; border-color: var(--c-border); color: var(--c-text-muted); display: inline-flex; justify-content: center; align-items: center; width: 42px; height: 42px; border-radius: 12px; cursor: pointer;">
                <i class="fa-solid fa-moon"></i>
            </button>
            <a href="<?= $base_url ?>/logout.php" class="btn btn-outline" style="padding: 0.6rem 1.2rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Yakin ingin keluar?');"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = themeToggle.querySelector('i');
    
    if (document.documentElement.classList.contains('dark-mode')) {
        themeIcon.className = 'fa-solid fa-sun';
        themeToggle.style.color = '#FFD23F';
        themeToggle.style.borderColor = '#FFD23F';
    }
    
    themeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark-mode');
        const isDark = document.documentElement.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        
        if (isDark) {
            themeIcon.className = 'fa-solid fa-sun';
            themeToggle.style.color = '#FFD23F';
            themeToggle.style.borderColor = '#FFD23F';
        } else {
            themeIcon.className = 'fa-solid fa-moon';
            themeToggle.style.color = 'var(--c-text-muted)';
            themeToggle.style.borderColor = 'var(--c-border)';
        }
    });
});
</script>

<main class="main-content">
