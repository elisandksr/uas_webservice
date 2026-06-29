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
        <div class="nav-cta" style="display: flex; gap: 10px;">
            <a href="<?= $base_url ?>/resep/create.php" class="btn btn-primary" style="box-shadow: none; padding: 0.6rem 1.5rem;"><i class="fa-solid fa-plus"></i> Tulis Resep</a>
            <a href="<?= $base_url ?>/logout.php" class="btn btn-outline" style="padding: 0.6rem 1.2rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Yakin ingin keluar?');"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </div>
</nav>

<main class="main-content">
