<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $conn->real_escape_string($_GET['id']);

// Fetch current data FIRST
$result = $conn->query("SELECT * FROM resep WHERE id='$id'");
if ($result->num_rows == 0) {
    echo "<div class='container'><p>Resep tidak ditemukan.</p></div>";
    require_once '../includes/footer.php';
    exit;
}
$row = $result->fetch_assoc();
$current_gambar = $row['gambar'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_resep = $conn->real_escape_string($_POST['nama_resep']);
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $bahan = $conn->real_escape_string($_POST['bahan']);
    $langkah = $conn->real_escape_string($_POST['langkah']);
    $sumber = $conn->real_escape_string($_POST['sumber']);
    
    $gambar = $current_gambar; // Default keep old image
    
    // Check if URL is provided
    if (!empty($_POST['gambar_url'])) {
        $gambar = $conn->real_escape_string($_POST['gambar_url']);
    }
    
    // Handle File Upload (will override URL if file is uploaded)
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar_file']['tmp_name'];
        $fileName = $_FILES['gambar_file']['name'];
        $fileSize = $_FILES['gambar_file']['size'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            if ($fileSize < 2097152) { // 2MB limit
                $uploadFileDir = '../assets/img/uploads/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $gambar = 'uploads/' . $newFileName;
                    
                    // Delete old image if it's a local file
                    if (!empty($current_gambar) && !filter_var($current_gambar, FILTER_VALIDATE_URL)) {
                        $oldFilePath = '../assets/img/' . $current_gambar;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                } else {
                    $error = 'Terjadi kesalahan saat mengunggah gambar ke direktori server.';
                }
            } else {
                $error = 'Ukuran file gambar maksimal 2MB.';
            }
        } else {
            $error = 'Format file tidak didukung (Gunakan JPG, PNG, GIF, atau WEBP).';
        }
    } else if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] !== UPLOAD_ERR_NO_FILE) {
         $error = 'Terjadi kesalahan saat mengunggah file. Kode error: ' . $_FILES['gambar_file']['error'];
    }

    if (empty($error)) {
        $sql = "UPDATE resep SET 
                nama_resep='$nama_resep', 
                kategori='$kategori', 
                bahan='$bahan', 
                langkah='$langkah', 
                sumber='$sumber',
                gambar='$gambar'
                WHERE id='$id'";
                
        if ($conn->query($sql) === TRUE) {
            $success = "Resep berhasil diperbarui!";
            // Update row array so the UI below shows the new data
            $row['nama_resep'] = $nama_resep;
            $row['kategori'] = $kategori;
            $row['bahan'] = $bahan;
            $row['langkah'] = $langkah;
            $row['sumber'] = $sumber;
            $row['gambar'] = $gambar;
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    }
}
?>

<div class="container" style="padding-top: 8rem; margin-bottom: 5rem;">
    <div class="section-header" style="margin-bottom: 2rem;">
        <h2 class="section-title"><i class="fa-solid fa-pen-to-square" style="color: var(--c-primary); margin-right: 10px;"></i> Edit Resep</h2>
        <a href="index.php" class="btn btn-outline" style="border-radius: 100px; padding: 0.6rem 1.5rem;"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="form-container">
        <h3 style="margin-bottom: 2rem; font-family: var(--f-heading); color: var(--c-secondary); border-bottom: 2px solid #F3F4F6; padding-bottom: 1rem;">
            <?= htmlspecialchars($row['nama_resep']) ?>
        </h3>

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

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_resep"><i class="fa-solid fa-utensils"></i> Nama Resep *</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-pen"></i>
                        <input type="text" id="nama_resep" name="nama_resep" class="form-control" required value="<?= htmlspecialchars($row['nama_resep']) ?>" placeholder="Contoh: Nasi Goreng Spesial">
                    </div>
                </div>

                <div class="form-group">
                    <label for="kategori"><i class="fa-solid fa-tags"></i> Kategori *</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-layer-group"></i>
                        <input type="text" id="kategori" name="kategori" class="form-control" required value="<?= htmlspecialchars($row['kategori']) ?>" placeholder="Contoh: Utama, Dessert, Minuman, dll">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="bahan"><i class="fa-solid fa-basket-shopping"></i> Bahan-bahan * <span style="font-size: 0.85rem; color: var(--c-text-muted); font-weight: 500; margin-left: auto;">(Tiap bahan di baris baru)</span></label>
                <textarea id="bahan" name="bahan" class="form-control" required placeholder="Tuliskan tiap bahan di baris baru..."><?= htmlspecialchars($row['bahan']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="langkah"><i class="fa-solid fa-list-ol"></i> Langkah Memasak * <span style="font-size: 0.85rem; color: var(--c-text-muted); font-weight: 500; margin-left: auto;">(Tiap instruksi di baris baru)</span></label>
                <textarea id="langkah" name="langkah" class="form-control" required placeholder="Tuliskan instruksi langkah demi langkah..."><?= htmlspecialchars($row['langkah']) ?></textarea>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-image"></i> Pratinjau Gambar Saat Ini</label>
                <?php if($row['gambar']): ?>
                    <?php 
                    $img_src = (filter_var($row['gambar'], FILTER_VALIDATE_URL)) ? $row['gambar'] : '../assets/img/' . $row['gambar'];
                    ?>
                    <div class="current-image-preview">
                        <img src="<?= $img_src ?>" alt="Current Image" onerror="this.src='https://via.placeholder.com/120x120?text=Error+Loading'">
                        <div>
                            <strong style="display: block; color: var(--c-text-main); margin-bottom: 4px; word-break: break-all;"><?= htmlspecialchars($row['gambar']) ?></strong>
                            <span style="font-size: 0.85rem; color: var(--c-text-muted);">Gambar yang sedang digunakan saat ini.</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="current-image-preview" style="padding: 1.5rem; gap: 1rem; color: var(--c-text-muted);">
                        <i class="fa-regular fa-image fa-2xl" style="color: #9CA3AF;"></i>
                        <span>Belum ada gambar yang ditentukan. Resep akan menggunakan emoji default.</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fa-solid fa-image"></i> Ganti Foto Masakan (Opsional)</label>
                    <div style="display: flex; gap: 8px; flex-direction: column;">
                        <div class="input-with-icon">
                            <i class="fa-solid fa-upload" style="margin-top: 4px;"></i>
                            <input type="file" id="gambar_file" name="gambar_file" class="form-control" accept="image/*" style="padding-top: 13px;">
                        </div>
                        <div style="text-align: center; color: var(--c-text-muted); font-size: 0.85rem; font-weight: 700;">ATAU</div>
                        <div class="input-with-icon">
                            <i class="fa-solid fa-globe"></i>
                            <input type="url" id="gambar_url" name="gambar_url" class="form-control" placeholder="Tempel URL gambar baru dari internet...">
                        </div>
                    </div>
                    <small style="color: var(--c-text-muted); font-size: 0.85rem; margin-top: 5px; display: block;">Jika Anda mengisi keduanya, file yang diunggah akan diprioritaskan.</small>
                </div>

                <div class="form-group" style="margin-bottom: 2.5rem;">
                    <label for="sumber"><i class="fa-solid fa-bookmark"></i> Sumber Resep (Opsional)</label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-at"></i>
                        <input type="text" id="sumber" name="sumber" class="form-control" value="<?= htmlspecialchars($row['sumber']) ?>" placeholder="Nama penulis / URL">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-large" style="width: 100%; border: none; border-radius: 16px; font-size: 1.15rem; letter-spacing: 0.5px; box-shadow: 0 10px 25px -5px rgba(255, 107, 53, 0.4);"><i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
