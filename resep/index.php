<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Base query
$sql = "SELECT * FROM resep WHERE 1=1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND nama_resep LIKE '%$search%'";
}

if (!empty($kategori)) {
    $kategori = $conn->real_escape_string($kategori);
    $sql .= " AND kategori = '$kategori'";
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);

// Get categories for filter
$cat_result = $conn->query("SELECT DISTINCT kategori FROM resep ORDER BY kategori ASC");
?>

<div class="container" style="padding-top: 8rem; margin-bottom: 5rem;">
    <!-- Page Header Banner -->
    <section class="explore-banner" style="padding: 3rem; margin-bottom: 2rem; background: linear-gradient(135deg, var(--c-primary), #FF8F60);">
        <div class="explore-banner-content" style="max-width: 800px;">
            <h1 style="font-size: 2.5rem; color: white; margin-bottom: 1rem;">
                <?= !empty($kategori) ? "Katalog Resep " . htmlspecialchars($kategori) : "Semua Resep Lokal" ?> 📖
            </h1>
            <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem;">Kelola dan temukan koleksi resep andalan Anda. Tambahkan masakan baru atau cari resep yang sudah ada untuk inspirasi masak hari ini.</p>
            <a href="<?= $base_url ?>/resep/create.php" class="btn btn-primary" style="background: var(--c-white); color: var(--c-primary); box-shadow: none;"><i class="fa-solid fa-plus"></i> Tambah Resep Baru</a>
        </div>
        <div class="explore-banner-img" style="font-size: 8rem; opacity: 0.5; top: 10px; right: 50px;">
            <?= !empty($kategori) && $kategori == 'Ayam' ? '🍗' : (!empty($kategori) && $kategori == 'Seafood' ? '🦐' : (!empty($kategori) && $kategori == 'Sayur' ? '🥦' : (!empty($kategori) && $kategori == 'Dessert' ? '🍰' : '🍲'))) ?>
        </div>
    </section>

    <!-- Search & Filter Toolbar -->
    <form action="" method="GET" class="search-bar" style="margin-bottom: 3rem;">
        <i class="fa-solid fa-search search-icon"></i>
        <input type="text" name="search" placeholder="Cari resep andalan (contoh: soto, rendang)..." value="<?= htmlspecialchars($search) ?>">
        
        <select name="kategori" style="border: none; outline: none; font-family: var(--f-body); color: var(--c-text-main); background: transparent; padding: 0 10px; border-left: 1px solid var(--c-border);">
            <option value="">Semua Kategori</option>
            <?php while($c = $cat_result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($c['kategori']) ?>" <?= $kategori == $c['kategori'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem; border-radius: var(--radius-sm);"><i class="fa-solid fa-search"></i> Cari</button>
        <?php if(!empty($search) || !empty($kategori)): ?>
            <a href="index.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem; border-radius: var(--radius-sm);">Reset</a>
        <?php endif; ?>
    </form>

    <div class="section-header">
        <h2 class="section-title">Hasil Pencarian</h2>
    </div>

    <!-- Recipe Grid -->
    <div class="recipe-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="recipe-card">
                    <div class="recipe-img-wrapper">
                        <span class="badge"><?= htmlspecialchars($row['kategori']) ?></span>
                        <?php 
                        $img_src = '';
                        if ($row['gambar']) {
                            $img_src = (filter_var($row['gambar'], FILTER_VALIDATE_URL)) ? $row['gambar'] : $base_url . '/assets/img/' . $row['gambar'];
                        } else {
                            $img_src = $base_url . '/assets/img/default.jpg';
                        }
                        ?>
                        <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($row['nama_resep']) ?>" class="recipe-img" onerror="this.src='https://via.placeholder.com/300x200?text=Sedap'">
                    </div>
                    
                    <div class="recipe-content">
                        <h3 class="recipe-title"><?= htmlspecialchars($row['nama_resep']) ?></h3>
                        <p style="color: var(--c-text-muted); font-size: 0.9rem; margin-bottom: 1rem;"><i class="fa-solid fa-user-pen"></i> <?= htmlspecialchars($row['sumber'] ?: 'Admin RecipeHub') ?></p>
                        
                        <div class="recipe-actions" style="margin-top: auto; display: flex; gap: 0.5rem;">
                            <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-secondary" style="flex: 1; border-radius: 12px; padding: 0.6rem;"><i class="fa-solid fa-fire-burner"></i> Masak</a>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="border-radius: 12px; padding: 0.6rem; border-color: var(--c-border); color: var(--c-text-muted);"><i class="fa-solid fa-pen"></i></a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="border-radius: 12px; padding: 0.6rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Yakin ingin menghapus resep ini?');"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: var(--c-white); border-radius: var(--radius-lg); border: 1px dashed var(--c-border);">
                <i class="fa-solid fa-face-frown-open fa-3x" style="color: var(--c-text-muted); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--c-text-main); margin-bottom: 0.5rem;">Resep tidak ditemukan</h3>
                <p style="color: var(--c-text-muted);">Mungkin Anda belum memiliki masakan di kategori ini. Yuk tambahkan!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
