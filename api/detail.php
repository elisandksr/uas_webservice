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

<style>
.container-wide {
    max-width: 1400px !important;
    width: 95% !important;
}
.detail-box-horizontal {
    display: flex;
    background: var(--c-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--c-border);
    margin-bottom: 24px;
    align-items: stretch;
}
.detail-img-col {
    width: 450px;
    min-height: 450px;
    flex-shrink: 0;
    background-color: #F9FAFB;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 2.5rem;
    border-right: 1px solid var(--c-border);
    padding: 3rem 2rem;
}
.polaroid-frame {
    background: #ffffff;
    padding: 16px 16px 48px 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid #E5E7EB;
    transform: rotate(-2deg);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    width: 340px;
}
.polaroid-frame:hover {
    transform: rotate(0deg) scale(1.04);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}
.polaroid-frame img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border: 1px solid #F3F4F6;
}
.polaroid-caption {
    font-family: 'Outfit', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--c-text-main);
    text-align: center;
    margin-top: 14px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.detail-content-compact {
    flex: 1;
    padding: 2.5rem 3.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.detail-grid-compact {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 3rem;
    margin-top: 1.2rem;
}
@media (max-width: 992px) {
    .detail-box-horizontal {
        flex-direction: column;
    }
    .detail-img-col {
        width: 100%;
        min-height: auto;
        border-right: none;
        border-bottom: 1px solid var(--c-border);
        padding: 3rem 1.5rem;
    }
}
@media (max-width: 768px) {
    .detail-grid-compact {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
@media print {
    @page {
        margin: 1.5cm;
    }
    body {
        margin: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
        font-family: Arial, sans-serif !important;
    }
    .navbar, footer, .no-print {
        display: none !important;
    }
    .container-wide {
        padding-top: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    .detail-box-horizontal {
        display: block !important;
        box-shadow: none !important;
        border: none !important;
        margin: 0 !important;
        background: transparent !important;
        padding: 0 !important;
    }
    .detail-grid-compact {
        display: block !important;
    }
    .detail-img-col {
        display: block !important;
        background: none !important;
        border: none !important;
        padding: 0 !important;
        width: 100% !important;
        min-height: auto !important; /* Mencegah kolom kosong yang memakan tempat */
        text-align: left !important;
        margin-bottom: 1rem !important;
    }
    .polaroid-frame {
        display: inline-block !important;
        border: none !important;
        box-shadow: none !important;
        transform: none !important;
        background: none !important;
        padding: 0 !important;
        width: auto !important;
    }
    .polaroid-frame img {
        width: 250px !important;
        height: auto !important;
        border-radius: 8px !important;
    }
    .polaroid-caption {
        display: none !important;
    }
    .detail-content-compact {
        display: block !important;
        padding: 0 !important;
    }
    .detail-section {
        margin-bottom: 1.5rem !important;
        page-break-inside: auto;
    }
    .detail-section h3 {
        border-bottom: 1px solid #000000 !important;
        color: #000000 !important;
        font-size: 1.2rem !important;
        padding-bottom: 3px !important;
        margin-bottom: 0.5rem !important;
    }
    .detail-list li, .detail-ol li {
        color: #000000 !important;
        font-size: 1rem !important;
    }
    .instructions-section {
        margin-top: 1.5rem !important;
    }
}
</style>

<div class="container container-wide" style="padding-top: 8rem; margin-bottom: 5rem;">
    <div class="no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="index.php" class="btn btn-outline" style="border-color: var(--c-border); color: var(--c-text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali Eksplorasi</a>
        <button onclick="window.print()" class="btn btn-outline" style="border-color: var(--c-primary); color: var(--c-primary);"><i class="fa-solid fa-print"></i> Cetak PDF</button>
    </div>
    
    <div class="detail-box-horizontal">
        <div class="detail-img-col">
            <div class="polaroid-frame">
                <img src="<?= htmlspecialchars($meal['strMealThumb']) ?>" alt="<?= htmlspecialchars($meal['strMeal']) ?>" onerror="this.src='https://via.placeholder.com/800x400?text=Sedap'">
                <div class="polaroid-caption"><?= htmlspecialchars($meal['strMeal']) ?></div>
            </div>
            
            <?php if($embed_url): ?>
                <div class="no-print" style="width: 100%; max-width: 340px; margin-top: 0.5rem;">
                    <h3 style="color: #E53E3E; border-bottom: 2px solid #FEE2E2; font-size: 1.1rem; margin-bottom: 1rem; padding-bottom: 0.5rem; font-family: var(--f-heading); font-weight: 700;"><i class="fa-brands fa-youtube"></i> Video Tutorial</h3>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--radius-md); box-shadow: var(--shadow-md);">
                        <iframe src="<?= $embed_url ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="detail-content-compact">
            <div style="display: flex; gap: 0.8rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <span class="badge" style="position: static; box-shadow: none; background: #FFF3EF; color: var(--c-primary);"><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($meal['strCategory']) ?></span>
                <span class="badge" style="position: static; box-shadow: none; background: #E8F0FE; color: var(--c-secondary);"><i class="fa-solid fa-earth-americas"></i> <?= htmlspecialchars($meal['strArea']) ?></span>
                <?php if(!empty($meal['strTags'])): ?>
                    <span style="font-size: 0.85rem; color: var(--c-text-muted); display: flex; align-items: center; gap: 0.4rem;"><i class="fa-solid fa-tags"></i> <?= htmlspecialchars($meal['strTags']) ?></span>
                <?php endif; ?>
            </div>

            <h1 class="detail-title" style="font-size: 2.2rem; margin-bottom: 1rem;"><?= htmlspecialchars($meal['strMeal']) ?></h1>
            
            <?php if(!empty($meal['strSource'])): ?>
                <a href="<?= htmlspecialchars($meal['strSource']) ?>" target="_blank" style="color: var(--c-primary); font-size: 0.9rem; font-weight: 600; margin-bottom: 1.5rem; display: inline-block;"><i class="fa-solid fa-link"></i> Kunjungi Sumber Asli Resep</a>
            <?php endif; ?>

            <div class="detail-grid-compact">
                <!-- Ingredients Section -->
                <div class="detail-section">
                    <h3 style="font-size: 1.3rem; margin-bottom: 1rem; padding-bottom: 0.5rem;">Bahan-bahan</h3>
                    <ul class="detail-list">
                        <?php foreach($ingredients as $ing): ?>
                            <li style="padding: 8px 0; font-size: 0.95rem;"><i class="fa-solid fa-check" style="color: #10B981; margin-right: 8px;"></i> <?= htmlspecialchars($ing) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Instructions Section -->
                <div class="detail-section instructions-section">
                    <h3 style="color: var(--c-secondary); border-bottom-color: #E8F0FE; font-size: 1.3rem; margin-bottom: 1rem; padding-bottom: 0.5rem;">Cara Memasak</h3>
                    <ol class="detail-ol">
                        <?php foreach($instructions as $inst): ?>
                            <?php if(trim($inst) != ''): ?>
                                <li style="padding: 6px 0; font-size: 0.95rem; line-height: 1.6; margin-bottom: 0.3rem; padding-left: 0.5rem;"><?= htmlspecialchars($inst) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
