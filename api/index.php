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
    <section class="explore-banner" style="padding: 3rem; margin-bottom: 2rem; background: linear-gradient(135deg, var(--c-secondary), #4F46E5);">
        <div class="explore-banner-content" style="max-width: 800px;">
            <h1 style="font-size: 2.5rem; color: white; margin-bottom: 1rem;">Keliling Dunia Lewat Rasa 🌍</h1>
            <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); margin-bottom: 1.5rem;">Cari ratusan resep otentik dari berbagai negara. Ketik bahan favorit Anda (dalam bahasa Inggris, misalnya: <em>beef, pasta, chicken</em>) dan temukan kelezatan baru!</p>
            <a href="?action=random" class="btn btn-primary" style="background: var(--c-accent); color: #B48E00; box-shadow: none;"><i class="fa-solid fa-dice"></i> Beri Saya Resep Kejutan!</a>
        </div>
        <div class="explore-banner-img" style="font-size: 8rem; opacity: 0.5; top: 10px; right: 50px;">
            🍽️
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
                        
                        <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>/preview" alt="<?= htmlspecialchars($meal['strMeal']) ?>" class="recipe-img">
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
