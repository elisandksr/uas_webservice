<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';

// Base query
$sql = "SELECT * FROM resep WHERE 1=1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (nama_resep LIKE '%$search%' OR bahan LIKE '%$search%')";
}

if (!empty($kategori)) {
    $kategori = $conn->real_escape_string($kategori);
    $sql .= " AND kategori = '$kategori'";
}

// Sorting logic
switch ($sort) {
    case 'terlama':
        $sql .= " ORDER BY created_at ASC";
        break;
    case 'az':
        $sql .= " ORDER BY nama_resep ASC";
        break;
    case 'za':
        $sql .= " ORDER BY nama_resep DESC";
        break;
    case 'terbaru':
    default:
        $sql .= " ORDER BY created_at DESC";
        break;
}

$result = $conn->query($sql);

// Get categories for filter
$cat_result = $conn->query("SELECT DISTINCT kategori FROM resep ORDER BY kategori ASC");
?>

<div class="container" style="padding-top: 8rem; margin-bottom: 5rem;">
    <!-- Page Header Banner -->
    <section class="explore-banner" style="padding: 1.5rem 2rem; margin-bottom: 3rem; border-radius: 16px; position: relative; overflow: hidden; display: flex; align-items: center; box-shadow: 0 10px 25px rgba(255, 107, 53, 0.15);">
        <!-- Background Image with Fade-to-Right Orange Overlay -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(255,107,53, 0.95) 0%, rgba(255,107,53, 0.8) 35%, rgba(255,107,53, 0) 70%); z-index: 2;"></div>
            <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?q=80&w=2070&auto=format&fit=crop" alt="Indonesian Food" style="width: 100%; height: 100%; object-fit: cover; object-position: right center; z-index: 1;">
        </div>
        
        <div class="explore-banner-content" style="max-width: 500px; width: 100%; position: relative; z-index: 3; text-align: left; margin-right: auto; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 100px; color: white; font-weight: 600; font-size: 0.75rem; margin-bottom: 0.8rem; border: 1px solid rgba(255,255,255,0.2);">
                <i class="fa-solid fa-book-open" style="margin-right: 4px;"></i> Koleksi Resep Pribadi
            </div>
            <h1 style="font-size: 2rem; color: white; margin-bottom: 0.6rem; font-family: var(--f-heading); text-shadow: 0 2px 5px rgba(0,0,0,0.2); line-height: 1.2; font-weight: 800; text-align: left;">
                <?= !empty($kategori) ? "Daftar Resep<br><span style='color: #FFD23F;'>" . htmlspecialchars($kategori) . " 📖</span>" : "Semua Resep<br><span style='color: #FFD23F;'>Lokal 📖</span>" ?>
            </h1>
            <p style="font-size: 0.95rem; color: rgba(255,255,255,0.9); margin-bottom: 1.2rem; line-height: 1.5; text-shadow: 0 1px 5px rgba(0,0,0,0.2); text-align: left;">Kelola dan temukan koleksi resep andalan Anda. Tambahkan masakan baru atau cari resep untuk inspirasi masak hari ini.</p>
            <a href="<?= $base_url ?>/resep/create.php" class="btn" style="background: white; color: var(--c-primary); padding: 0.6rem 1.5rem; font-size: 0.9rem; border-radius: 100px; font-weight: 700; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)';">
                <i class="fa-solid fa-plus" style="color: #FF6B35; font-size: 1rem;"></i> Tambah Resep Baru
            </a>
        </div>
    </section>

    <!-- Search & Filter Toolbar -->
    <form action="" method="GET" class="search-bar" style="margin-bottom: 3rem;">
        <i class="fa-solid fa-search search-icon"></i>
        <input type="text" name="search" placeholder="Cari nama atau bahan (contoh: ayam, santan)..." value="<?= htmlspecialchars($search) ?>" style="flex: 1;">
        
        <!-- Filter Kategori -->
        <select name="kategori" style="border: none; outline: none; font-family: var(--f-body); color: var(--c-text-main); background: transparent; padding: 0 10px; border-left: 1px solid var(--c-border); min-width: 130px;">
            <option value="">Semua Kategori</option>
            <?php while($c = $cat_result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($c['kategori']) ?>" <?= $kategori == $c['kategori'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <!-- Sorting Data -->
        <select name="sort" style="border: none; outline: none; font-family: var(--f-body); color: var(--c-text-main); background: transparent; padding: 0 10px; border-left: 1px solid var(--c-border); min-width: 140px;">
            <option value="terbaru" <?= $sort == 'terbaru' ? 'selected' : '' ?>>Urutan Terbaru</option>
            <option value="terlama" <?= $sort == 'terlama' ? 'selected' : '' ?>>Urutan Terlama</option>
            <option value="az" <?= $sort == 'az' ? 'selected' : '' ?>>Nama (A-Z)</option>
            <option value="za" <?= $sort == 'za' ? 'selected' : '' ?>>Nama (Z-A)</option>
        </select>
        
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem; border-radius: var(--radius-sm);"><i class="fa-solid fa-search"></i> Cari</button>
        <?php if(!empty($search) || !empty($kategori) || $sort != 'terbaru'): ?>
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
