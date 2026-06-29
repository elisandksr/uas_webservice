<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

$api_base = "https://www.themealdb.com/api/json/v1/1/";
$meals = [];
$api_title = "Jelajahi Resep Mancanegara";

// Fetch categories for dropdown
$cat_response = @file_get_contents($api_base . "categories.php");
$categories = [];
if ($cat_response) {
    $cat_data = json_decode($cat_response, true);
    if(isset($cat_data['categories'])) {
        $categories = $cat_data['categories'];
    }
}

// Determine which endpoint to call
if ($action == 'random') {
    $response = @file_get_contents($api_base . "random.php");
    $api_title = "Resep Kejutan Untuk Anda!";
} elseif (!empty($search)) {
    $response = @file_get_contents($api_base . "search.php?s=" . urlencode($search));
    $api_title = "Hasil Pencarian: " . htmlspecialchars($search);
} elseif (!empty($kategori)) {
    $response = @file_get_contents($api_base . "filter.php?c=" . urlencode($kategori));
    $api_title = "Kategori: " . htmlspecialchars($kategori);
} else {
    // Default show some recipes (e.g., search with empty string or specific letter)
    $response = @file_get_contents($api_base . "search.php?s=chicken");
    $api_title = "Rekomendasi Resep Ayam Dunia";
}

if ($response) {
    $data = json_decode($response, true);
    if (isset($data['meals']) && is_array($data['meals'])) {
        $meals = $data['meals'];
    }
}
?>

<div class="container" style="padding-top: 8rem;">
    <!-- Page Header Banner -->
    <section class="explore-banner" style="padding: 1.5rem 2rem; margin-bottom: 2rem; border-radius: 16px; position: relative; overflow: hidden; display: flex; align-items: center; box-shadow: 0 10px 25px rgba(43, 69, 223, 0.15);">
        <!-- Background Image with Fade-to-Right Blue Overlay -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to right, rgba(43,69,223, 0.95) 0%, rgba(43,69,223, 0.8) 35%, rgba(43,69,223, 0) 70%); z-index: 2;"></div>
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop" alt="World Food" style="width: 100%; height: 100%; object-fit: cover; object-position: right center; z-index: 1;">
        </div>
        
        <div class="explore-banner-content" style="max-width: 500px; width: 100%; position: relative; z-index: 3; text-align: left; margin-right: auto; display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 100px; color: white; font-weight: 600; font-size: 0.75rem; margin-bottom: 0.8rem; border: 1px solid rgba(255,255,255,0.2);">
                <i class="fa-solid fa-globe" style="margin-right: 4px;"></i> Eksplorasi Kuliner Global
            </div>
            <h1 style="font-size: 2rem; color: white; margin-bottom: 0.6rem; font-family: var(--f-heading); text-shadow: 0 2px 5px rgba(0,0,0,0.2); line-height: 1.2; font-weight: 800; text-align: left;">Keliling Dunia<br><span style="color: #FFD23F;">Lewat Rasa 🌍</span></h1>
            <p style="font-size: 0.95rem; color: rgba(255,255,255,0.9); margin-bottom: 1.2rem; line-height: 1.5; text-shadow: 0 1px 5px rgba(0,0,0,0.2); text-align: left;">Cari ratusan resep otentik. Ketik bahan favorit Anda (misal: <em>beef, pasta</em>) dan temukan kelezatan baru!</p>
            <a href="?action=random" class="btn" style="background: white; color: var(--c-secondary); padding: 0.6rem 1.5rem; font-size: 0.9rem; border-radius: 100px; font-weight: 700; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)';">
                <i class="fa-solid fa-dice" style="color: #FF6B35; font-size: 1rem;"></i> Beri Resep Kejutan
            </a>
        </div>
    </section>

    <!-- Search & Filter Toolbar -->
    <form action="" method="GET" class="search-bar" style="margin-bottom: 3rem;">
        <i class="fa-solid fa-search search-icon"></i>
        <input type="text" name="search" placeholder="Cari resep (contoh: pasta, salad, soup)..." value="<?= htmlspecialchars($search) ?>">
        
        <select name="kategori" style="border: none; outline: none; font-family: var(--f-body); color: var(--c-text-main); background: transparent; padding: 0 10px; border-left: 1px solid var(--c-border);">
            <option value="">Semua Kategori</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['strCategory']) ?>" <?= $kategori == $cat['strCategory'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['strCategory']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem; border-radius: var(--radius-sm);"><i class="fa-solid fa-search"></i> Cari</button>
        <?php if(!empty($search) || !empty($kategori) || $action == 'random'): ?>
            <a href="index.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem; border-radius: var(--radius-sm);">Reset</a>
        <?php endif; ?>
    </form>

    <div class="section-header">
        <h2 class="section-title"><?= $api_title ?></h2>
    </div>

    <!-- Recipe Grid -->
    <div class="recipe-grid">
        <?php if (count($meals) > 0): ?>
            <?php foreach($meals as $meal): ?>
                <div class="recipe-card">
                    <div class="recipe-img-wrapper">
                        <?php if(isset($meal['strCategory'])): ?>
                            <span class="badge"><?= htmlspecialchars($meal['strCategory']) ?></span>
                        <?php elseif(!empty($kategori)): ?>
                            <span class="badge"><?= htmlspecialchars($kategori) ?></span>
                        <?php endif; ?>
                        
                        <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>" alt="<?= htmlspecialchars($meal['strMeal']) ?>" class="recipe-img">
                    </div>
                    
                    <div class="recipe-content">
                        <h3 class="recipe-title"><?= htmlspecialchars($meal['strMeal']) ?></h3>
                        
                        <?php if(isset($meal['strArea'])): ?>
                            <p style="color: var(--c-primary); font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;"><i class="fa-solid fa-earth-americas"></i> Resep Asli <?= htmlspecialchars($meal['strArea']) ?></p>
                        <?php else: ?>
                            <p style="color: var(--c-text-muted); font-size: 0.9rem; margin-bottom: 1rem;"><i class="fa-solid fa-utensils"></i> Resep Pilihan</p>
                        <?php endif; ?>
                        
                        <div class="recipe-actions" style="margin-top: auto;">
                            <a href="detail.php?id=<?= $meal['idMeal'] ?>" class="btn btn-secondary" style="width: 100%; border-radius: 12px;"><i class="fa-solid fa-fire-burner"></i> Cara Memasak</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: var(--c-white); border-radius: var(--radius-lg); border: 1px dashed var(--c-border);">
                <i class="fa-solid fa-face-frown-open fa-3x" style="color: var(--c-text-muted); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--c-text-main); margin-bottom: 0.5rem;">Resep tidak ditemukan</h3>
                <p style="color: var(--c-text-muted);">Coba gunakan kata kunci dalam bahasa Inggris ya, misalnya "chicken", "beef", atau "cake".</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
