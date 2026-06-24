<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Get image filename to delete file
    $img_query = $conn->query("SELECT gambar FROM resep WHERE id='$id'");
    if ($img_query->num_rows > 0) {
        $img = $img_query->fetch_assoc()['gambar'];
        if ($img && file_exists('../assets/img/' . $img)) {
            unlink('../assets/img/' . $img);
        }
    }
    
    $sql = "DELETE FROM resep WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
