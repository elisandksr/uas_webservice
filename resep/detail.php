<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $conn->real_escape_string($_GET['id']);
$result = $conn->query("SELECT * FROM resep WHERE id='$id'");

if ($result->num_rows == 0) {
    echo "<div class='container' style='padding-top: 8rem;'><p>Resep tidak ditemukan.</p></div>";
    require_once '../includes/footer.php';
    exit;
}

$row = $result->fetch_assoc();

// Parse ingredients and instructions to arrays for better display if they are separated by newlines
$bahan_arr = explode("\n", trim($row['bahan']));
$langkah_arr = explode("\n", trim($row['langkah']));
?>

<div class="container" style="padding-top: 8rem; margin-bottom: 5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="index.php" class="btn btn-outline" style="border-color: var(--c-border); color: var(--c-text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog</a>
        <div style="display: flex; gap: 0.5rem;">
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline" style="border-color: var(--c-secondary); color: var(--c-secondary);"><i class="fa-solid fa-pen"></i> Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-primary" style="background-color: #EF4444; box-shadow: none;" onclick="return confirm('Apakah Anda yakin ingin menghapus resep ini?');"><i class="fa-solid fa-trash"></i> Hapus</a>
        </div>
    </div>
    
    <div class="detail-box">
        <?php 
        $img_src = '';
        if ($row['gambar']) {
            $img_src = (filter_var($row['gambar'], FILTER_VALIDATE_URL)) ? $row['gambar'] : $base_url . '/assets/img/' . $row['gambar'];
        } else {
            $img_src = $base_url . '/assets/img/default.jpg';
        }
        ?>
        <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($row['nama_resep']) ?>" class="detail-img" onerror="this.src='https://via.placeholder.com/800x400?text=Sedap'">
        
        <div class="detail-content">
            <div style="display: flex; gap: 0.8rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <span class="badge" style="position: static; box-shadow: none; background: #FFF3EF; color: var(--c-primary);"><i class="fa-solid fa-utensils"></i> Kategori: <?= htmlspecialchars($row['kategori']) ?></span>
                <?php if($row['sumber']): ?>
                    <span style="font-size: 0.85rem; color: var(--c-text-muted); display: flex; align-items: center; gap: 0.4rem;"><i class="fa-solid fa-link"></i> Sumber: <?= htmlspecialchars($row['sumber']) ?></span>
                <?php endif; ?>
            </div>

            <h1 class="detail-title"><?= htmlspecialchars($row['nama_resep']) ?></h1>
            
            <div class="detail-grid">
                <!-- Ingredients Section -->
                <div class="detail-section">
                    <h3>Bahan-bahan yang Dibutuhkan</h3>
                    <ul class="detail-list">
                        <?php foreach($bahan_arr as $bahan): ?>
                            <?php if(trim($bahan) != ''): ?>
                                <li><i class="fa-solid fa-check" style="color: #10B981; margin-right: 8px;"></i> <?= htmlspecialchars($bahan) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Instructions Section -->
                <div class="detail-section">
                    <h3 style="color: var(--c-secondary); border-bottom-color: #E8F0FE;">Langkah Memasak</h3>
                    <ol class="detail-ol">
                        <?php foreach($langkah_arr as $langkah): ?>
                            <?php if(trim($langkah) != ''): ?>
                                <li><?= htmlspecialchars($langkah) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
