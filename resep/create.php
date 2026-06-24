<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_resep = $conn->real_escape_string($_POST['nama_resep']);
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $bahan = $conn->real_escape_string($_POST['bahan']);
    $langkah = $conn->real_escape_string($_POST['langkah']);
    $sumber = $conn->real_escape_string($_POST['sumber']);
    $gambar = $conn->real_escape_string($_POST['gambar']);

    $sql = "INSERT INTO resep (nama_resep, kategori, bahan, langkah, gambar, sumber) 
            VALUES ('$nama_resep', '$kategori', '$bahan', '$langkah', '$gambar', '$sumber')";
            
    if ($conn->query($sql) === TRUE) {
        $success = "Resep berhasil ditambahkan!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="container" style="padding-top: 8rem; margin-bottom: 5rem;">
    <div class="section-header" style="margin-bottom: 2rem;">
        <h2 class="section-title"><i class="fa-solid fa-circle-plus" style="color: var(--c-primary); margin-right: 10px;"></i> Tambah Resep Baru</h2>
        <a href="index.php" class="btn btn-outline" style="border-radius: 100px; padding: 0.6rem 1.5rem;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>
 
    <div class="form-container">
        <?php if($error): ?>
            <div style="background-color: var(--c-danger); color: white; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.8rem; display: flex; align-items: center; gap: 0.8rem; font-weight: 500;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= $error ?></span>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div style="background-color: #10B981; color: white; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.8rem; display: flex; align-items: center; gap: 0.8rem; font-weight: 500; flex-wrap: wrap;">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= $success ?></span>
                <a href="index.php" style="color: white; text-decoration: underline; margin-left: auto; font-weight: 600;">Lihat Katalog Resep <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        <?php endif; ?>
 
        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_resep">Nama Resep *</label>
                <input type="text" id="nama_resep" name="nama_resep" class="form-control" required placeholder="Contoh: Nasi Goreng Spesial">
            </div>
 
            <div class="form-group">
                <label for="kategori">Kategori *</label>
                <input type="text" id="kategori" name="kategori" class="form-control" required placeholder="Contoh: Utama, Dessert, Minuman, dll">
            </div>
 
            <div class="form-group">
                <label for="bahan">Bahan-bahan * (Tuliskan tiap bahan di baris baru)</label>
                <textarea id="bahan" name="bahan" class="form-control" required placeholder="Contoh:&#10;3 piring nasi dingin&#10;2 butir telur&#10;kecap manis secukupnya"></textarea>
            </div>
 
            <div class="form-group">
                <label for="langkah">Langkah Memasak * (Tuliskan tiap instruksi di baris baru)</label>
                <textarea id="langkah" name="langkah" class="form-control" required placeholder="Contoh:&#10;1. Haluskan bawang merah dan putih.&#10;2. Tumis bumbu hingga harum.&#10;3. Masukkan nasi dan aduk rata."></textarea>
            </div>
 
            <div class="form-group">
                <label for="gambar">URL Gambar Masakan (Opsional)</label>
                <input type="url" id="gambar" name="gambar" class="form-control" placeholder="Tempel URL gambar internet (misal: dari TheMealDB)...">
            </div>
 
            <div class="form-group" style="margin-bottom: 2.5rem;">
                <label for="sumber">Sumber Resep (Opsional)</label>
                <input type="text" id="sumber" name="sumber" class="form-control" placeholder="Nama penulis / URL website">
            </div>
 
            <button type="submit" class="btn btn-primary btn-large" style="width: 100%; border: none;"><i class="fa-solid fa-floppy-disk"></i> Simpan Resep</button>
        </form>
    </div>
</div>
 
<?php require_once '../includes/footer.php'; ?>
