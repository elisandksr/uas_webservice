<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Get search, category filters & sorting
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
$order_clause = "ORDER BY created_at DESC";
switch ($sort) {
    case 'terlama':
        $order_clause = "ORDER BY created_at ASC";
        break;
    case 'az':
        $order_clause = "ORDER BY nama_resep ASC";
        break;
    case 'za':
        $order_clause = "ORDER BY nama_resep DESC";
        break;
}

$sql .= " " . $order_clause . " LIMIT 9";
$result_latest = $conn->query($sql);

// Get categories for filter dropdown
$cat_result = $conn->query("SELECT DISTINCT kategori FROM resep ORDER BY kategori ASC");

// Get Statistics for Dashboard
$total_resep_query = $conn->query("SELECT COUNT(*) as total FROM resep");
$total_resep = $total_resep_query->fetch_assoc()['total'] ?? 0;

$total_kategori_query = $conn->query("SELECT COUNT(DISTINCT kategori) as total FROM resep");
$total_kategori = $total_kategori_query->fetch_assoc()['total'] ?? 0;

$resep_baru_query = $conn->query("SELECT COUNT(*) as total FROM resep WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$resep_baru = $resep_baru_query->fetch_assoc()['total'] ?? 0;
?>

<!-- Premium Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, #FF6B35 0%, #FF8F60 100%), url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); padding: 13rem 0 8rem 0; position: relative;">
    <div class="container hero-grid">
        <div class="hero-content">
            <div class="hero-badge" style="background: rgba(255, 255, 255, 0.25); border: 1px solid rgba(255,255,255,0.4); box-shadow: 0 4px 15px rgba(0,0,0,0.05); color: #fff;"><i class="fa-solid fa-sparkles"></i> Selamat Datang di Dapur Digital Anda</div>
            <h1 style="text-shadow: 0 4px 15px rgba(0,0,0,0.1);">Masak Jadi Lebih <span style="color: #FFD23F;">Menyenangkan!</span></h1>
            <p style="font-size: 1.25rem; text-shadow: 0 2px 5px rgba(0,0,0,0.05);">Tidak perlu bingung mau masak apa hari ini. Temukan ratusan inspirasi resep lezat, simpan resep andalan keluarga, dan mulai berkreasi.</p>
            <div class="hero-actions">
                <a href="<?= $base_url ?>/resep/index.php" class="btn btn-white" style="background-color: white; color: var(--c-primary); box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4); font-size: 1.1rem; padding: 1rem 2.5rem;"><i class="fa-solid fa-book-open"></i> Mulai Memasak</a>
                <a href="<?= $base_url ?>/api/index.php" class="btn btn-outline" style="border-color: rgba(255,255,255,0.6); color: white; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px);"><i class="fa-solid fa-earth-americas"></i> Resep Dunia</a>
            </div>
        </div>
        
        <div class="hero-graphic">
            <div class="abstract-shape shape-main" style="background: url('https://i.pinimg.com/736x/aa/ab/5f/aaab5f08a619f3afb534717e09b7de00.jpg') no-repeat center; background-size: cover; border: 4px solid rgba(255,255,255,0.2);"></div>
            <div class="abstract-shape shape-circle" style="box-shadow: inset 0 0 40px rgba(0,0,0,0.1);"></div>
            <div class="abstract-shape shape-dots"></div>
            
            <div class="hero-card" style="backdrop-filter: blur(15px); background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(255,255,255,0.5);">
                <div style="background: linear-gradient(135deg, #FF6B35, #FF8F60); padding: 1.2rem; border-radius: 14px; color: white; box-shadow: 0 8px 15px rgba(255,107,53,0.3);">
                    <i class="fa-solid fa-fire fa-2x"></i>
                </div>
                <div>
                    <h4 style="font-size: 1.2rem; margin-bottom: 0.2rem; color: #111827;">Resep Spesial</h4>
                    <p style="color: var(--c-text-muted); font-size: 0.95rem; margin:0;">Rekomendasi Hari Ini ✨</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Dashboard Statistics -->
<div class="container" style="position: relative; z-index: 20; margin-top: -4.5rem; margin-bottom: 3rem;">
    <div class="stats-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <!-- Total Resep Card -->
        <div class="stat-card" style="background: linear-gradient(135deg, var(--c-primary), #FF8F60); padding: 1.8rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(255,107,53,0.3); display: flex; align-items: center; position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer;" onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 25px 45px rgba(255,107,53,0.4)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 15px 35px rgba(255,107,53,0.3)';">
            <div style="position: relative; z-index: 2;">
                <p style="color: rgba(255,255,255,0.8); font-size: 1.05rem; font-weight: 600; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px;">Total Resep</p>
                <h3 style="font-size: 3rem; font-weight: 800; margin: 0; color: white; line-height: 1;"><?= $total_resep ?></h3>
            </div>
            <i class="fa-solid fa-book-open" style="position: absolute; right: -15px; bottom: -20px; font-size: 7rem; color: white; opacity: 0.15; transform: rotate(-15deg); transition: transform 0.4s ease;"></i>
        </div>
        
        <!-- Kategori Card -->
        <div class="stat-card" style="background: linear-gradient(135deg, var(--c-secondary), #4F65F0); padding: 1.8rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(43,69,223,0.3); display: flex; align-items: center; position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer;" onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 25px 45px rgba(43,69,223,0.4)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 15px 35px rgba(43,69,223,0.3)';">
            <div style="position: relative; z-index: 2;">
                <p style="color: rgba(255,255,255,0.8); font-size: 1.05rem; font-weight: 600; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px;">Kategori</p>
                <h3 style="font-size: 3rem; font-weight: 800; margin: 0; color: white; line-height: 1;"><?= $total_kategori ?></h3>
            </div>
            <i class="fa-solid fa-layer-group" style="position: absolute; right: -10px; bottom: -10px; font-size: 7rem; color: white; opacity: 0.15; transform: rotate(-10deg); transition: transform 0.4s ease;"></i>
        </div>
        
        <!-- Resep Baru Card -->
        <div class="stat-card" style="background: linear-gradient(135deg, #10B981, #34D399); padding: 1.8rem; border-radius: 20px; box-shadow: 0 15px 35px rgba(16,185,129,0.3); display: flex; align-items: center; position: relative; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer;" onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 25px 45px rgba(16,185,129,0.4)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 15px 35px rgba(16,185,129,0.3)';">
            <div style="position: relative; z-index: 2;">
                <p style="color: rgba(255,255,255,0.8); font-size: 1.05rem; font-weight: 600; margin: 0 0 0.5rem 0; text-transform: uppercase; letter-spacing: 1px;">Baru (7 Hari)</p>
                <h3 style="font-size: 3rem; font-weight: 800; margin: 0; color: white; line-height: 1;"><?= $resep_baru ?></h3>
            </div>
            <i class="fa-solid fa-sparkles" style="position: absolute; right: -10px; bottom: -15px; font-size: 7rem; color: white; opacity: 0.15; transform: rotate(10deg); transition: transform 0.4s ease;"></i>
        </div>
    </div>
</div>

<!-- Search & Filter Panel -->
<div class="container" style="margin-bottom: 4rem;">
    <form action="" method="GET" class="search-bar" style="padding: 15px 25px; border-radius: 100px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); background: white; display: flex; align-items: center; gap: 15px; border: 1px solid var(--c-border); transition: all 0.3s ease;">
        <i class="fa-solid fa-magnifying-glass" style="color: var(--c-primary); font-size: 1.4rem;"></i>
        <input type="text" name="search" placeholder="Cari resep andalan keluarga Anda..." value="<?= htmlspecialchars($search) ?>" style="font-size: 1.1rem; border: none; outline: none; flex: 1;">
        
        <select name="kategori" style="border: none; outline: none; font-family: var(--f-body); color: var(--c-text-main); background: transparent; padding: 0 15px; border-left: 1px solid var(--c-border); font-size: 1.05rem; cursor: pointer;">
            <option value="">Semua Kategori</option>
            <?php if ($cat_result && $cat_result->num_rows > 0): ?>
                <?php while($c = $cat_result->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($c['kategori']) ?>" <?= $kategori == $c['kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['kategori']) ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
        <select name="sort" style="border: none; outline: none; font-family: var(--f-body); color: var(--c-text-main); background: transparent; padding: 0 15px; border-left: 1px solid var(--c-border); font-size: 1.05rem; cursor: pointer;">
            <option value="terbaru" <?= $sort == 'terbaru' ? 'selected' : '' ?>>Urutan Terbaru</option>
            <option value="terlama" <?= $sort == 'terlama' ? 'selected' : '' ?>>Urutan Terlama</option>
            <option value="az" <?= $sort == 'az' ? 'selected' : '' ?>>Nama (A-Z)</option>
            <option value="za" <?= $sort == 'za' ? 'selected' : '' ?>>Nama (Z-A)</option>
        </select>
        
        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; border-radius: 100px; font-size: 1rem;"><i class="fa-solid fa-search"></i> Cari</button>
        <?php if(!empty($search) || !empty($kategori) || $sort != 'terbaru'): ?>
            <a href="index.php" class="btn btn-outline" style="padding: 0.8rem 2rem; border-radius: 100px; font-size: 1rem;">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="container" style="margin-bottom: 6rem;">
    <!-- Section Header -->
    <div class="section-header" style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 class="section-title" style="font-size: 2.2rem; display: flex; align-items: center; gap: 12px; margin: 0;">
                <i class="fa-solid fa-utensils" style="color: var(--c-primary);"></i>
                <?= (!empty($search) || !empty($kategori)) ? 'Hasil Pencarian Resep' : 'Resep Andalan Terbaru' ?>
            </h2>
            <p style="color: var(--c-text-muted); margin-top: 0.5rem; font-size: 1.05rem; margin-bottom: 0;">
                <?= (!empty($search) || !empty($kategori)) ? 'Menampilkan resep lokal yang cocok dengan kriteria pencarian' : 'Daftar resep pilihan keluarga yang siap Anda kreasikan hari ini' ?>
            </p>
        </div>
        <?php if (empty($search) && empty($kategori)): ?>
            <a href="<?= $base_url ?>/resep/index.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem; font-size: 0.95rem; border-radius: 100px; display: inline-flex; align-items: center; gap: 8px;">
                Lihat Semua Resep <i class="fa-solid fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>

    <!-- Recipe Grid -->
    <div class="recipe-grid">
        <?php if ($result_latest && $result_latest->num_rows > 0): ?>
            <?php 
            $i = 0;
            while($row = $result_latest->fetch_assoc()): 
                $emojis = ['🍜', '🍣', '🥗', '🍮', '🍲', '🍝'];
                $fallback_emoji = $emojis[$i % count($emojis)];
            ?>
                <div class="recipe-card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.04);">
                    <div class="recipe-img-wrapper" style="height: 200px;">
                        <span class="badge" style="background: var(--c-primary); color: white;"><?= htmlspecialchars($row['kategori']) ?></span>
                        <?php if($row['gambar']): ?>
                            <?php 
                            $img_src = (filter_var($row['gambar'], FILTER_VALIDATE_URL)) ? $row['gambar'] : $base_url . '/assets/img/' . $row['gambar'];
                            ?>
                            <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($row['nama_resep']) ?>" class="recipe-img" onerror="this.src='https://via.placeholder.com/400x300?text=Sedap'">
                        <?php else: ?>
                            <div class="recipe-img-fallback" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; background: var(--c-bg);">
                                <?= $fallback_emoji ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="recipe-content" style="padding: 1.5rem;">
                        <h3 class="recipe-title" style="font-size: 1.25rem; margin-bottom: 0.4rem; font-weight: 700;"><?= htmlspecialchars($row['nama_resep']) ?></h3>
                        <p style="color: var(--c-text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;"><i class="fa-solid fa-user-pen"></i> <?= htmlspecialchars($row['sumber'] ?: 'Admin RecipeHub') ?></p>
                        
                        <div class="recipe-actions" style="margin-top: auto; display: flex; gap: 0.5rem; width: 100%;">
                            <a href="<?= $base_url ?>/resep/detail.php?id=<?= $row['id'] ?>" class="btn btn-secondary" style="flex: 1; border-radius: 12px; padding: 0.6rem 1rem; font-size: 0.95rem; font-weight: 600;"><i class="fa-solid fa-fire-burner"></i> Masak</a>
                            <a href="<?= $base_url ?>/resep/edit.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="border-radius: 12px; padding: 0.6rem; border-color: var(--c-border); color: var(--c-text-muted); display: inline-flex; justify-content: center; align-items: center; width: 42px; height: 42px;"><i class="fa-solid fa-pen"></i></a>
                            <a href="<?= $base_url ?>/resep/delete.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="border-radius: 12px; padding: 0.6rem; border-color: #EF4444; color: #EF4444; display: inline-flex; justify-content: center; align-items: center; width: 42px; height: 42px;" onclick="return confirm('Yakin ingin menghapus resep ini?');"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            <?php 
                $i++;
            endwhile; 
            ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; background: var(--c-white); border-radius: var(--radius-lg); border: 1px dashed var(--c-border); box-shadow: var(--shadow-sm); width: 100%;">
                <i class="fa-solid fa-book-open fa-4x" style="color: var(--c-text-muted); margin-bottom: 1.5rem; opacity: 0.4;"></i>
                <h3 style="color: var(--c-text-main); margin-bottom: 0.5rem; font-size: 1.5rem; font-weight: 700;">Belum ada resep tersimpan</h3>
                <p style="color: var(--c-text-muted); margin-bottom: 2rem; font-size: 1.05rem;">Buku resep digital Anda masih kosong atau tidak ada yang sesuai kata kunci.</p>
                <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                    <a href="<?= $base_url ?>/resep/create.php" class="btn btn-primary" style="border-radius: 100px; display: inline-flex; align-items: center; gap: 8px;"><i class="fa-solid fa-plus"></i> Tambah Resep Pertama</a>
                    <?php if(!empty($search) || !empty($kategori)): ?>
                        <a href="index.php" class="btn btn-outline" style="border-radius: 100px;">Bersihkan Pencarian</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


</div>

<?php require_once 'includes/footer.php'; ?>
