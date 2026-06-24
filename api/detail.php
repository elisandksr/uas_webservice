<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = urlencode($_GET['id']);
$api_url = "https://www.themealdb.com/api/json/v1/1/lookup.php?i=" . $id;

$response = @file_get_contents($api_url);
$meal = null;

if ($response) {
    $data = json_decode($response, true);
    if (isset($data['meals']) && is_array($data['meals']) && count($data['meals']) > 0) {
        $meal = $data['meals'][0];
    }
}

if (!$meal) {
    echo "<div class='container' style='padding-top: 8rem;'><p>Mohon maaf, resep yang Anda cari tidak dapat ditemukan.</p></div>";
    require_once '../includes/footer.php';
    exit;
}

// Extract ingredients and measures
$ingredients = [];
for ($i = 1; $i <= 20; $i++) {
    $ing = $meal['strIngredient'.$i];
    $meas = $meal['strMeasure'.$i];
    
    if (!empty($ing) && trim($ing) != '') {
        $ingredients[] = $meas . ' ' . $ing;
    }
}

// Parse instructions
$instructions = explode("\r\n", $meal['strInstructions']);

// Handle Youtube embed
$youtube_url = $meal['strYoutube'];
$embed_url = '';
if (!empty($youtube_url)) {
    // Convert watch?v= format to embed format
    if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $youtube_url, $matches)) {
        $embed_url = 'https://www.youtube.com/embed/' . $matches[1];
    }
}
?>

<div class="container" style="padding-top: 8rem; margin-bottom: 5rem;">
    <a href="index.php" class="btn btn-outline" style="margin-bottom: 2rem; display: inline-flex; border-color: var(--c-border); color: var(--c-text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali Eksplorasi</a>
    
    <div class="detail-box">
        <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>" alt="<?= htmlspecialchars($meal['strMeal']) ?>" class="detail-img">
        
        <div class="detail-content">
            <div style="display: flex; gap: 0.8rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <span class="badge" style="position: static; box-shadow: none; background: #FFF3EF; color: var(--c-primary);"><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($meal['strCategory']) ?></span>
                <span class="badge" style="position: static; box-shadow: none; background: #E8F0FE; color: var(--c-secondary);"><i class="fa-solid fa-earth-americas"></i> <?= htmlspecialchars($meal['strArea']) ?></span>
                <?php if(!empty($meal['strTags'])): ?>
                    <span style="font-size: 0.85rem; color: var(--c-text-muted); display: flex; align-items: center; gap: 0.4rem;"><i class="fa-solid fa-tags"></i> <?= htmlspecialchars($meal['strTags']) ?></span>
                <?php endif; ?>
            </div>

            <h1 class="detail-title"><?= htmlspecialchars($meal['strMeal']) ?></h1>
            
            <?php if(!empty($meal['strSource'])): ?>
                <a href="<?= htmlspecialchars($meal['strSource']) ?>" target="_blank" style="color: var(--c-primary); font-size: 0.9rem; font-weight: 600;"><i class="fa-solid fa-link"></i> Kunjungi Sumber Asli Resep</a>
            <?php endif; ?>

            <div class="detail-grid">
                <!-- Ingredients Section -->
                <div class="detail-section">
                    <h3>Bahan-bahan yang Dibutuhkan</h3>
                    <ul class="detail-list">
                        <?php foreach($ingredients as $ing): ?>
                            <li><i class="fa-solid fa-check" style="color: #10B981; margin-right: 8px;"></i> <?= htmlspecialchars($ing) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Instructions Section -->
                <div class="detail-section">
                    <h3 style="color: var(--c-secondary); border-bottom-color: #E8F0FE;">Cara Memasak</h3>
                    <ol class="detail-ol">
                        <?php foreach($instructions as $inst): ?>
                            <?php if(trim($inst) != ''): ?>
                                <li><?= htmlspecialchars($inst) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                    
                    <?php if($embed_url): ?>
                        <div style="margin-top: 3rem;">
                            <h3 style="color: #E53E3E; border-bottom-color: #FEE2E2;"><i class="fa-brands fa-youtube"></i> Video Tutorial (Bahasa Inggris)</h3>
                            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--radius-md); box-shadow: var(--shadow-md); margin-top: 1rem;">
                                <iframe src="<?= $embed_url ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
